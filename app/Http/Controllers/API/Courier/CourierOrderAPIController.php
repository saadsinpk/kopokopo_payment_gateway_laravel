<?php

namespace App\Http\Controllers\API\Courier;

use Exception;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierOrderAPIController extends Controller
{
    private $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    /**
     * Display a listing of the Order.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'required_with:current_item|integer|min:1',
            'current_item' => 'required_with:limit|integer|min:0',
            'datetime_start' => 'date',
            'datetime_end' => 'date',
        ]);
        try {
            $hasMoreOrders = false;
            $ordersQuery = Order::where('order_status','!=','waiting')->orderBy('id', 'desc')->with('offlinePaymentMethod');

            if ($request->has('datetime_start')) {
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', substr($request->datetime_start,0,10) . ' 00:00:00');
                $ordersQuery->where('created_at', '>=', $startDate);
            }

            if ($request->has('datetime_end')) {
                $dataTerm = Carbon::createFromFormat('Y-m-d H:i:s', substr($request->datetime_end,0,10) . ' 23:59:59');

                $ordersQuery->where('created_at', '<=', $dataTerm);
            }

            $ordersQuery->where('courier_id', Auth::user()->courier->id);

            if ($request->has('limit')) {
                $hasMoreOrders = $ordersQuery
                    ->count() > ($request->current_item + $request->limit);

                $ordersQuery->skip($request->current_item)
                    ->take($request->limit);
            }

            $orders = $ordersQuery->get();
            $orders = $orders->toArray();
            Carbon::setlocale(config('app.locale'));
            foreach ($orders as $key => $order) {
                $orders[$key]['created_at'] = (new Carbon($order['created_at']))->tz(app()->config->get('app.timezone'))->format('Y-m-d H:i:s');
            }

            return $this->sendResponse(['has_more_orders' => $hasMoreOrders, 'orders' => $orders], 'Orders retrieved successfully');
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /**
     * Display the specified Order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $order = Order::with('offlinePaymentMethod')->find($id);

            if (!isset($order) || $order->courier_id != Auth::user()->courier->id) {
                return $this->sendError(__('Not found'));
            }

            return $this->sendResponse($order, 'Order retrieved successfully');
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
    /**
     * Check if exists a new Order
     *
     * @return \Illuminate\Http\Response
     */
    public function checkNewOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|min:0',
        ]);
        try {
            $orders = Order::where('courier_id', Auth::user()->courier->id)
                ->where('order_status','!=', 'pending')
                ->where('id', '>', $request->order_id)
                ->orderBy('id', 'desc')
                ->get();

            return $this->sendResponse($orders, __('Orders retrieved successfully'));
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /**
     * Update the status of a Order
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|min:1',
            'status' => 'required|string|in:accepted,rejected,collected,delivered,completed,cancelled',
            'delivery_address_id' => 'required_if:status,delivered',
        ]);

        $order = Order::find($request->order_id);

        if (!isset($order) || $order->courier_id != Auth::user()->courier->id) {
            return $this->sendError('Pedido não encontrado');
        }

        try {

            if ($order->payment_status == "paid" && !$order->offline_payment_method_id && ($request->status == 'cancelled' || $request->status == 'rejected')) {
                //Refund the order based on the payment method
                $this->orderRepository->refundOrderPayment($order);
            }
            switch ($request->status) {
                case 'accepted':
                    if ($order->order_status != 'pending') {
                        return $this->sendError(__('Order is not pending'));
                    }
                    $order->order_status = $request->status;
                    break;
                case 'rejected':
                    if ($order->order_status != 'pending') {
                        return $this->sendError(__('You can\'t reject an order that is not pending'));
                    }
                    $order->order_status = $request->status;
                    $order->payment_status = "cancelled";
                    $order->payment_status_date = Carbon::now();
                    break;
                case 'collected':
                    if ($order->order_status != 'accepted') {
                        return $this->sendError(__('You can\'t collect an order that is not accepted'));
                    }
                    $order->order_status = $request->status;
                    break;
                case 'delivered':
                    $deliveryPlaces = json_decode($order->delivery_locations_data, true);
                    $keys = array_column($deliveryPlaces, 'id');
                    $index = array_search($request->delivery_address_id, $keys);

                    if ($index !== false) {
                        $deliveryPlaces[$index]['delivered'] = true;
                        $deliveryPlaces[$index]['delivered_date'] = Carbon::now();
                    }
                    $delivered = true;
                    foreach ($deliveryPlaces as $delivery) {
                        if (!$delivery['delivered']) {
                            $delivered = false;
                        }
                    }
                    if ($delivered && !$order->need_return_to_pickup_location) {
                        $order->order_status = 'completed';
                    }
                    $order->delivery_locations_data = json_encode($deliveryPlaces);
                    break;
                case 'completed':
                    $order->order_status = $request->status;
                    break;
                case 'cancelled':
                    $order->order_status = $request->status;
                    $order->payment_status = "cancelled";
                    $order->payment_status_date = Carbon::now();
                    $order->order_status_date = Carbon::now();
                    break;
                default:
                    break;
            }

            $order->save();

            return $this->sendResponse($order, __('Order status updated successfully'));
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
}

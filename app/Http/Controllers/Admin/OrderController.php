<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrderDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Repositories\CourierRepository;
use App\Repositories\OfflinePaymentMethodRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;

class OrderController extends AppBaseController
{
    /** @var OrderRepository $orderRepository*/
    private $orderRepository;
    /** @var UserRepository $userRepository*/
    private $userRepository;
    /** @var CourierRepository $courierRepository*/
    private $courierRepository;
    /** @var OfflinePaymentMethodRepository $offlinePaymentMethodRepository*/
    private $offlinePaymentMethodRepository;

    public function __construct(OrderRepository $orderRepo,UserRepository $userRepo,CourierRepository $courierRepo,OfflinePaymentMethodRepository $offlinePaymentMethodRepo)
    {
        $this->orderRepository = $orderRepo;
        $this->userRepository = $userRepo;
        $this->courierRepository = $courierRepo;
        $this->offlinePaymentMethodRepository = $offlinePaymentMethodRepo;
    }

    /**
     * Display a listing of the Order.
     *
     * @param OrderDataTable $orderDataTable
     *
     * @return Response
     */
    public function index(OrderDataTable $orderDataTable)
    {
        return $orderDataTable->render('admin.orders.index');
    }

    /**
     * Show the form for creating a new Order.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param CreateOrderRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderRequest $request)
    {
        $input = $request->all();

        $order = $this->orderRepository->create($input);

        Flash::success('Order saved successfully.');

        return redirect(route('admin.orders.index'));
    }

    /**
     * Display the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $order = $this->orderRepository->with(['user','offlinePaymentMethod','courier','courier.user'])->find($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('admin.orders.index'));
        }

        return view('admin.orders.show')->with('order', $order);
    }

    /*
     * Get order address and status of delivery for each status
     * The admin can get any order on this
     */
    public function ajaxGetAddressesHtml(Request $request){
        $order = Order::where('id',$request->order_id)->firstOrFail();
        return view('orders.ajax.addresses',compact('order'));
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $order = $this->orderRepository->find($id);
        $customer = $this->userRepository->where('users.id', $order->user_id)->pluck('name','id');
        $courier = $this->courierRepository->join('users','users.id','=','couriers.user_id')->where('couriers.id', $order->courier_id)->pluck('users.name','couriers.id');
        $offlinePaymentMethods = $this->offlinePaymentMethodRepository->pluck('name','id');
        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('admin.orders.index'));
        }

        return view('admin.orders.edit')->with(compact('order','customer','courier','offlinePaymentMethods'));
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrderRequest $request)
    {
        if($request->offline_payment_method_id == 0 && empty($request->payment_gateway)){
            return redirect()->back()->withErrors([
                'offline_payment_method_id' => __('Please select offline payment method or one payment gateway'),
            ])->withInput($request->all());
        }

        //check the pickup and delivery locations
        $pickupAddress = json_decode($request->collect_address_data ?? "{}");
        $deliveryPlaces = $request->delivery_address_data ?? [];
        if(!(isset($pickupAddress->geometry->location->lat) && isset($pickupAddress->geometry->location->lng))){
            return redirect()->back()->withErrors([
                'collect_address_data' => __('Please insert and select a valid pickup location'),
            ])->withInput($request->all());
        }
        $deliveryPlaceTmp = json_decode($request->delivery_address_tmp ?? "{}",true);
        $deliveryPlacesArray = [];
        foreach ($deliveryPlaces as $deliveryPlace) {
            $deliveryPlace = json_decode($deliveryPlace,true);
            if (isset($deliveryPlace['geometry']['location']['lat']) && isset($deliveryPlace['geometry']['location']['lng'])) {
                $deliveryPlacesArray[] = $deliveryPlace+['id'=> Str::random(),'delivered' => false,'delivered_date' => null];
            }
        }

        if (isset($deliveryPlaceTmp['geometry']['location']['lat']) && isset($deliveryPlaceTmp['geometry']['location']['lng'])) {
            $deliveryPlacesArray[] = $deliveryPlaceTmp+['id'=> Str::random(),'delivered' => false,'delivered_date' => null];
        }
        if(count($deliveryPlacesArray) == 0){
            return redirect()->back()->withErrors([
                'delivery_places' => __('Please insert one valid delivery location'),
            ])->withInput($request->all());
        }
        $order = $this->orderRepository->find($id);
        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('admin.orders.index'));
        }
        $updateData = [
            'user_id' => $request->user_id,
            'courier_id' => $request->courier_id,
            'pickup_location' => $request->pickup_location,
            'pickup_location_data' => $request->collect_address_data,
            'save_pickup_location_for_next_order' => $request->save_pickup_location_for_next_order,
            'delivery_locations_data' => json_encode($deliveryPlacesArray),
            'need_return_to_pickup_location' => $request->need_return_to_pickup_location,
            'distance' => $request->distance,
            'courier_value' => $request->courier_value,
            'app_value' => $request->app_value,
            'total_value' => $request->courier_value+$request->app_value,
            'customer_observation' => $request->customer_observation,
            'offline_payment_method_id' => $request->offline_payment_method_id,
            'payment_gateway' => $request->payment_gateway,
            'payment_status' => $request->payment_status,
            'payment_status_date' => ($request->payment_status != $order->payment_staus) ? now() : $order->payment_status_date,
            'order_status' => $request->order_status,
            'order_status_date' => ($request->order_status != $order->order_status) ? now() : $order->order_status_date,
        ];

        $order = $this->orderRepository->update($updateData, $id);

        Flash::success('Order updated successfully.');

        return redirect(route('admin.orders.index'));
    }

    /**
     * Remove the specified Order from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $order = $this->orderRepository->find($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('admin.orders.index'));
        }

        if ($order->payment_status == "paid" && !$order->offline_payment_method_id) {
            //Refund the order based on the payment method
            $this->orderRepository->refundOrderPayment($order);
        }
        
        $this->orderRepository->delete($id);

        Flash::success('Order deleted successfully.');

        return redirect(route('admin.orders.index'));
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\Courier;
use App\Models\Order;
use App\Repositories\CourierRepository;
use App\Repositories\OrderRepository;
use App\Http\Controllers\Controller;
use App\Repositories\UploadRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;

class OrderAPIController extends Controller
{
    private $orderRepository;
    private $courierRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $orderRepository, CourierRepository $courierRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->courierRepository = $courierRepository;
    }


    /**
     * Display a listing of the Orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'required_with:current_item|integer|min:1',
            'current_item' => 'required_with:limit|integer|min:0'
        ]);
        try {
            $hasMoreOrders = false;
            $ordersQuery = Order::where('user_id', Auth::user()->id)->with('courier.user', 'offlinePaymentMethod')->orderBy('id', 'desc');

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
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /**
     * Display the specified Order.
     * GET|HEAD /orders/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        /** @var Order $order */
        $order = $this->orderRepository->with(['courier.user', 'offlinePaymentMethod'])->find($id);

        if (empty($order) || $order->user_id != auth()->user()->id) {
            return $this->sendError('Order not found');
        }

        return $this->sendResponse($order->toArray(), 'Order retrieved successfully');
    }

    /*
     * Simulate the order pricing
     */
    public function simulate(Request $request)
    {
        $pickupAddress = json_decode($request->collect_address_data ?? "{}");
        $deliveryPlaces = $request->delivery_address_data ?? [];
        $deliveryPlaceTmp = json_decode($request->delivery_address_tmp ?? "{}");
        if ((!(isset($pickupAddress->geometry->location->lat) && isset($pickupAddress->geometry->location->lng)))
            || (count($deliveryPlaces) == 0 && !(isset($deliveryPlaceTmp->geometry->location->lat) && isset($deliveryPlaceTmp->geometry->location->lng)))
            || (!(isset($request->slug) && strlen($request->slug) > 0))
        ) {
            //dont filled everything needed to simulate
            return [
                'success' => 1,
                'enabled' => false,
            ];
        }

        $totalValue = 0;
        $distance = 0;
        $returnDistance = 0;
        $courier = $this->courierRepository->with('user')->findByField('slug', $request->slug)->firstOrFail();


        if (!(isset($pickupAddress->geometry->location->lat) && isset($pickupAddress->geometry->location->lng))) {
            return [
                'success' => 0,
                'message' => __('Pickup address is not valid'),
                'price' => $totalValue,
                'distance' => $distance
            ];
        }





        if (count($deliveryPlaces) == 0 && !(isset($deliveryPlaceTmp->geometry->location->lat) && isset($deliveryPlaceTmp->geometry->location->lng))) {
            return [
                'success' => 1,
                'price' => $totalValue,
                'distance' => $distance,
                'enabled' => false
            ];
        }
        $deliveryPlacesArray = [];
        foreach ($deliveryPlaces as $deliveryPlace) {
            $deliveryPlace = json_decode($deliveryPlace);
            if (isset($deliveryPlace->geometry->location->lat) && isset($deliveryPlace->geometry->location->lng)) {
                $deliveryPlacesArray[] = $deliveryPlace;
                //$deliveryPlacesString .= $deliveryPlace->geometry->location->lat.",".$deliveryPlace->geometry->location->lng."|";
            }
        }
        //if the user left some address without click in + and it's considered an address too
        if (isset($deliveryPlaceTmp->geometry->location->lat) && isset($deliveryPlaceTmp->geometry->location->lng)) {
            $deliveryPlacesArray[] = $deliveryPlaceTmp;
        }

        $gmaps = new \yidas\googleMaps\Client(['key' => setting('google_maps_key')]);
        $additionalStops = 0;
        $lastDeliveryPlace = null;
        foreach ($deliveryPlacesArray as $key => $deliveryPlace) {
            if ($key == 0) {
                $pickupPlaceString = $pickupAddress->geometry->location->lat . "," . $pickupAddress->geometry->location->lng;
            } else {
                $pickupPlaceString = $deliveryPlaceString ?? ($pickupAddress->geometry->location->lat . "," . $pickupAddress->geometry->location->lng);
                $additionalStops++;
            }
            $deliveryPlaceString = $deliveryPlace->geometry->location->lat . "," . $deliveryPlace->geometry->location->lng;
            $distanceMatrix = $gmaps->distanceMatrix($pickupPlaceString, $deliveryPlaceString, [
                'mode' => 'driving',
                'units' => ((setting('distance_unit', 'mi') == 'mi') ? 'imperial' : 'metric')
            ]);
            if ($distanceMatrix['status'] != 'OK') {
                return [
                    'success' => 0,
                    'message' => __('Error getting distance matrix'),
                    'price' => 0,
                    'distance' => 0
                ];
            }
            $distance += $distanceMatrix['rows'][0]['elements'][0]['distance']['value'] / 1000;
            $lastDeliveryPlace = $deliveryPlace;
        }
        $billDistance = $distance;

        if ($request->return_required) {
            $pickupPlaceString = $pickupAddress->geometry->location->lat . "," . $pickupAddress->geometry->location->lng;
            $deliveryPlaceString = $lastDeliveryPlace->geometry->location->lat . "," . $lastDeliveryPlace->geometry->location->lng;
            $distanceMatrix = $gmaps->distanceMatrix($deliveryPlaceString, $pickupPlaceString, [
                'mode' => 'driving',
                'units' => ((setting('distance_unit', 'mi') == 'mi') ? 'imperial' : 'metric')
            ]);
            if ($distanceMatrix['status'] != 'OK') {
                return [
                    'success' => 0,
                    'message' => __('Error getting distance matrix'),
                    'price' => 0,
                    'distance' => 0
                ];
            }
            $returnDistance = $distanceMatrix['rows'][0]['elements'][0]['distance']['value'] / 1000;
            $distance += $returnDistance;
        }

        if ($courier->using_app_pricing) {
            if (setting('base_distance') > 0) {
                $billDistance = $billDistance - setting('base_distance');
                if ($billDistance < 0) {
                    $billDistance = 0;
                    $returnDistance = $returnDistance - ($billDistance * -1);
                    if ($returnDistance < 0) {
                        $returnDistance = 0;
                    }
                }
            }
            $totalValue = (setting('base_price') ?? 0) + ($billDistance * setting('additional_distance_pricing')) + ($returnDistance * setting('return_distance_pricing')) + ($additionalStops * setting('additional_stop_tax', 0));
        } else {
            //use user pricing settings
            if ($courier->base_distance > 0) {
                $billDistance = $billDistance - $courier->base_distance;
                if ($billDistance < 0) {
                    $returnDistance = $returnDistance - ($billDistance * -1);
                    $billDistance = 0;
                    if ($returnDistance < 0) {
                        $returnDistance = 0;
                    }
                }
            }
            $totalValue = ($courier->base_price ?? 0) + ($billDistance * $courier->additional_distance_pricing) + ($returnDistance * $courier->return_distance_pricing) + ($additionalStops * $courier->additional_stop_tax ?? 0);
        }

        $totalValue += $totalValue * (setting('app_tax', 0)) / 100;

        return [
            'success' => 1,
            'price' => getPrice($totalValue),
            'originalPrice' => $totalValue,
            'originalDistance' => $distance,
            'distance' => number_format($distance, 3) . ' ' . setting('distance_unit', 'mi'),
            'enabled' => ($totalValue > 0 && $distance > 0)
        ];
    }

    /*
     * Store an order
     */
    public function store(Request $request)
    {
        $courier = $this->courierRepository->with('user')->findByField('slug', $request->slug)->firstOrFail();
        if ($courier->active) {
            $simulation = $this->simulate($request);
            if ($simulation['success'] && $simulation['enabled']) {
                $collectAddress = json_decode($request->collect_address_data ?? "{}");
                $deliveryPlaces = $request->delivery_address_data ?? [];


                $deliveryPlaceTmp = json_decode($request->delivery_address_tmp ?? "{}", true);
                $deliveryPlacesArray = [];
                foreach ($deliveryPlaces as $deliveryPlace) {
                    $deliveryPlace = json_decode($deliveryPlace, true);
                    if (isset($deliveryPlace['geometry']['location']['lat']) && isset($deliveryPlace['geometry']['location']['lng'])) {
                        $deliveryPlacesArray[] = $deliveryPlace + ['id' => Str::random(), 'delivered' => false, 'delivered_date' => null];
                    }
                }

                if (isset($deliveryPlaceTmp['geometry']['location']['lat']) && isset($deliveryPlaceTmp['geometry']['location']['lng'])) {
                    $deliveryPlacesArray[] = $deliveryPlaceTmp + ['id' => Str::random(), 'delivered' => false, 'delivered_date' => null];
                }


                $totalPrice = $simulation['originalPrice'];
                $appTax = setting('app_tax', 0);
                $courierPrice = round(getNumberBeforePercentage($totalPrice, $appTax), 2, PHP_ROUND_HALF_DOWN);

                $appPrice = $totalPrice - $courierPrice;

                $paymentMethodType = $request->payment_method_type;
                $paymentMethodId = $request->payment_method;


                //create order
                try {
                    $order = Order::create([
                        'user_id' => auth()->user()->id,
                        'courier_id' => $courier->id,
                        'pickup_location' => $request->collect_place,
                        'pickup_location_data' => json_encode($collectAddress),
                        'save_pickup_location_for_next_order' => $request->save_data ?? false,
                        'delivery_locations_data' => json_encode($deliveryPlacesArray),
                        'need_return_to_pickup_location' => $request->return_required ?? false,
                        'distance' => $simulation['originalDistance'],
                        'courier_value' => $courierPrice,
                        'app_value' => $appPrice,
                        'total_value' => $totalPrice,
                        'customer_observation' => $request->observation ?? null,
                        'offline_payment_method_id' => (($paymentMethodType == 'offline') ? $paymentMethodId : 0),
                        'payment_gateway' => (($paymentMethodType == 'offline') ? null : $paymentMethodId),
                        'gateway_id' => null,
                        'payment_status' => 'pending',
                        'payment_status_date' => Carbon::now(),
                        'order_status' => (($paymentMethodType == 'offline') ? 'pending' : 'waiting'),
                        'order_status_date' => Carbon::now(),
                    ]);
                } catch (\Exception $e) {
                    report($e);
                    return response()->json(['success' => false, 'active' => true, 'message' => 'An error has occurred, please try again'], 400);
                }
                if ($order->id) {
                    return response()->json([
                        'success' => true,
                        'active' => false,
                        'id' => $order->id
                    ], 200);
                }
            } else {
                //return the simulation error
                return $simulation;
            }
        } else {
            return response()->json([
                'success' => false,
                'active' => false,
                'message' => __('The selected courier is no longer active')
            ], 400);
        }
    }

    /*
     * Create a payment intent for stripe
     */
    public function initializePayment(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        if ($order->payment_gateway == 'stripe') {
            if (isset($order->gateway_id)) {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($order->gateway_id);
                return ['clientSecret' => $paymentIntent->client_secret];
            }
            $intent = \Stripe\PaymentIntent::create([
                'amount' => intval($order->total_value * 100),
                'currency' => setting('currency', 'USD'),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);
            $order->gateway_id = $intent->id;
            $order->save();
            return ['clientSecret' => $intent->client_secret];
        } elseif ($order->payment_gateway == 'mercado_pago') {
            \MercadoPago\SDK::setAccessToken(setting('mercado_pago_secret'));

            $item = new \MercadoPago\Item();
            $item->title = __('Order') . ' #' . $order->id . ' of ' . $order->distance . ' ' . setting('distance_unit', 'mi');
            $item->quantity = 1;
            $item->unit_price = $order->total_value;
            $item->category_id = "virtual_goods";
            $item->currency_id = "BRL";

            $preference = new \MercadoPago\Preference();
            $preference->items = array($item);
            $preference->external_reference = $order->id;
            $preference->back_urls = array(
                "success" => url('/orders/') . "/" . $order->id,
                "failure" => url('/orders/') . "/" . $order->id,
                "pending" => url('/orders/') . "/" . $order->id
            );

            $preference->auto_return = "approved";
            $preference->notification_url = URL::to('api/webhook/mercadopago');
            $preference->payment_methods = array(
                "installments" => 1
            );
            $preference->save();
            return ['preference' => $preference->id];
        } else if ($order->payment_gateway == 'razorpay') {
            if (isset($order->gateway_order_id)) {
                return ['payment_order_id' => $order->gateway_order_id];
            }
            $orderOptions = array(
                'receipt' => $order->id,
                'amount' => intval($order->total_value * 100),
                'currency' => 'INR',
                'notes' => array('order_id' => $order->id),
            );

            $api = new Api(setting('razorpay_key'), setting('razorpay_secret'));
            $paymentOrder = $api->order->create($orderOptions);
            $order->gateway_order_id = $paymentOrder->id;
            $order->save();
            return ['payment_order_id' => $paymentOrder->id];
        }
        return ['message' => __('Payment method not supported')];
    }

    /*
     * Return the order status with image and info for the user
     */
    public function getStatus(Request $request)
    {
        $order = $this->orderRepository->find($request->order_id);
        if ($order->user_id != auth()->user()->id) {
            abort(403);
        }
        $need_recheck = 0;
        $image = "";
        $statusTitle = "";
        $statusDesc = "";
        switch ($order->order_status) {
            case "waiting":
                $need_recheck = 0;
                $image = asset('img/order_pending.gif');
                $statusTitle = __("Waiting for payment");
                $statusDesc = __("The order is only send for the courier after the payment is completed");
                break;
            case "pending":
                $need_recheck = 10000; //10 seconds
                $image = asset('img/order_pending.gif');
                $statusTitle = __("Waiting for delivery person to accept the order");
                $statusDesc = __("You don't have to wait here, the delivery person received the order and will accept as soon as possible");
                break;
            case "accepted":
                $need_recheck = 20000; //20 seconds
                $image = asset('img/order_accepted.png');
                $statusTitle = __("Order Accepted");
                $statusDesc = __("The delivery person is heading to the pick-up location");
                break;
            case "rejected":
                $need_recheck = 0; // not need to recheck
                $image = asset('img/order_rejected.png');
                $statusTitle = __("Order Rejected");
                $statusDesc = __("The delivery person rejected the order");
                break;
            case "collected":
                $need_recheck = 20000;
                $image = asset('img/order_accepted.png');
                $statusTitle = __("Order Collected");
                $statusDesc = __("The order has already been collected by the delivery person");
                break;
            case "delivered":
                $need_recheck = 20000;
                $image = asset('img/order_accepted.png');
                $statusTitle = __("Order Delivered");
                $statusDesc = __("The order has already been delivered to all addresses and the delivery person is returning to the pick-up location");
                break;
            case "completed":
                $need_recheck = 0;
                $image = asset('img/order_completed.png');
                $statusTitle = __("Order Completed");
                $statusDesc = __("Order completed successfully!");
                break;
            case "cancelled":
                $need_recheck = 0;
                $image = asset('img/order_cancelled.png');
                $statusTitle = __("Order Cancelled");
                $statusDesc = __("The order was canceled");
                break;
        }
        return response()->json([
            'success' => true,
            'need_recheck' => $need_recheck,
            'image' => $image,
            'status_title' => $statusTitle,
            'status_desc' => $statusDesc,
            'status_date' => getDateHumanFormat($order->order_status_date, true),
            'show_courier_position' => in_array($order->order_status, ['accepted', 'collected', 'delivered']),
        ]);
    }

    /*
     * Cancel an order if it's pending yet
     */
    public function cancel(Request $request)
    {
        $order = $this->orderRepository->find($request->order_id);
        if ($order->user_id != auth()->user()->id || !($order->order_status == 'pending' || $order->order_status == 'waiting')) {
            return [
                'success' => 0,
                'message' => __("You cannot cancel this order as it has already been accepted. Contact the courier for more information.")
            ];
        }
        if ($order->payment_status == "paid") {

            if (!$order->offline_payment_method_id) {
                //Refund the order based on the payment method
                $this->orderRepository->refundOrderPayment($order);
                $order->payment_status = "cancelled";
                $order->payment_status_date = Carbon::now();
                
            } else {
                $order->payment_status = "cancelled";
                $order->payment_status_date = Carbon::now();
            }
        }
        $order->order_status = "cancelled";
        $order->order_status_date = Carbon::now();
        $order->save();

        return [
            'success' => 1
        ];
    }


    public function getCourierPosition(Request $request)
    {
        $order = $this->orderRepository->with('courier')->find($request->order_id);
        if ($order->user_id != auth()->user()->id || !in_array($order->order_status, ['accepted', 'collected', 'delivered'])) {
            return [
                'success' => 0,
                'message' => __("You cannot see the courier position")
            ];
        }

        return [
            'success' => 1,
            'lat' => (float)$order->courier->lat,
            'lng' => (float)$order->courier->lng
        ];
    }
    public function payWithPayPal(Request $request, $id)
    {
        $order = Order::find($id);

        if (!isset($order) || ($order->payment_gateway != "paypal" && $order->payment_gateway != "PayPal") || $order->payment_status != "pending") {
            abort(404);
        }
        return view('orders.api.paypal_payment', compact('order'));
    }

    public function payWithMercadoPago(Request $request, $id)
    {
        $order = Order::find($id);

        if (!isset($order) || $order->payment_gateway != "mercado_pago" || $order->payment_status != "pending") {
            abort(404);
        }
        return view('orders.api.mercado_pago_payment', compact('order'));
    }

    public function payWithFlutterwave(Request $request, $id)
    {
        $order = Order::find($id);


        $this->uploadRepository = new UploadRepository(app());
        $upload = $this->uploadRepository->getByUuid(setting('app_logo', ''));
        $appLogo = asset('img/logo_default.png');
        if ($upload && $upload->hasMedia('default')) {
            $appLogo = $upload->getFirstMediaUrl('default');
        }
        if (!isset($order) || $order->payment_gateway != "flutterwave" || $order->payment_status != "pending") {
            abort(404);
        }
        return view('orders.api.flutterwave_payment', compact('order', 'appLogo'));
    }

    public function payWithRazorpay(Request $request, $id)
    {
        $order = Order::find($id);

        $this->uploadRepository = new UploadRepository(app());
        $upload = $this->uploadRepository->getByUuid(setting('app_logo', ''));
        $appLogo = asset('img/logo_default.png');
        if ($upload && $upload->hasMedia('default')) {
            $appLogo = $upload->getFirstMediaUrl('default');
        }
        if (!isset($order) || $order->payment_gateway != "razorpay" || $order->payment_status != "pending") {
            abort(404);
        }
        return view('orders.api.razorpay_payment', compact('order', 'appLogo'));
    }

    public function checkPaymentByOrderID($id)
    {
        try {
            $order = Order::findOrFail($id);

            return $this->sendResponse($order->payment_status, 'Payment order retrieved successfully');
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    public function paymentSuccessScreen($id, Request $request)
    {
        $order = $this->orderRepository->find($id);
        if ($order->user_id != auth()->user()->id) {
            abort(403);
        }
        if (!$order->offline_payment_method_id) {
            /*
                 * Here update the payment intent if necessary to dont show old information for customer
                 * But It doesn't substitute the webhooks updates, it's just for update the status quickly and show for the customer we can use it
                 */
            if ($order->payment_gateway == 'stripe' && $request->get('payment_intent')) {
                $this->orderRepository->updateStripePaymentStatusByGatewayId($request->get('payment_intent'));
            } elseif ($order->payment_gateway == 'paypal' && $request->get('payment_gateway_id')) {
                $this->orderRepository->updatePaypalPaymentStatusByGatewayId($request->get('payment_gateway_id'));
            } else if ($order->payment_gateway == 'flutterwave' && $request->get('transaction_id')) {
                $this->orderRepository->updateFlutterwavePaymentStatusByGatewayId($request->get('transaction_id'));
            } else if ($order->payment_gateway == 'razorpay' && $request->get('razorpay_payment_id')) {
                $this->orderRepository->updateRazorpayPaymentStatusByGatewayId($request->get('razorpay_payment_id'));
            }
        }

        return view('orders.api.success', compact('order'));
    }
}

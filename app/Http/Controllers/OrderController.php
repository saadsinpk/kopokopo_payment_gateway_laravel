<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Message;
use App\Models\Order;
use App\Repositories\CourierRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UploadRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Stripe\Stripe;

class OrderController extends AppBaseController
{
    private $orderRepository;
    private $deliverBoyRepository;

    public function __construct(OrderRepository $orderRepository,CourierRepository $deliverBoyRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->deliverBoyRepository = $deliverBoyRepository;
    }

    /*
    * User orders list
    */
    public function index(){

        if(!auth()->check()){
            return redirect('/');
        }
        $orders = Order::with(['courier','courier.user','OfflinePaymentMethod'])->where('user_id', auth()->user()->id)->orderBy('id','desc')->get();
        $background_image = (new UploadRepository(app()))->getByUuid( setting('background_image', ''));
        if ($background_image && $background_image->hasMedia('default')) {
            $background_image = $background_image->getFirstMediaUrl('default');
        }
        return view('orders.index', compact('background_image','orders'));
    }

    /*
     * Show a specific order
     * @param $id - Order id
     */
    public function show(int $id, Request $request){
        $order = $this->orderRepository->with(['courier','courier.user','OfflinePaymentMethod'])->find($id);
        if($order->user_id != auth()->user()->id){
            abort(403);
        }
        $background_image = (new UploadRepository(app()))->getByUuid( setting('background_image', ''));
        if ($background_image && $background_image->hasMedia('default')) {
            $background_image = $background_image->getFirstMediaUrl('default');
        }
        if(!$order->offline_payment_method_id){
            /*
             * Here update the payment intent if necessary to dont show old information for customer
             * But It doesn't substitute the webhooks updates, it's just for update the status quickly and show for the customer we can use it
             */
            if($order->payment_gateway == 'stripe' && $request->get('payment_intent')){
                $this->orderRepository->updateStripePaymentStatusByGatewayId($request->get('payment_intent'));
                return redirect(route('orders.show',$id));
            }elseif($order->payment_gateway == 'paypal' && $request->get('payment_gateway_id')){
                $this->orderRepository->updatePaypalPaymentStatusByGatewayId($request->get('payment_gateway_id'));
                return redirect(route('orders.show',$id));
            }else if($order->payment_gateway == 'flutterwave' && $request->get('transaction_id')){
                $this->orderRepository->updateFlutterwavePaymentStatusByGatewayId($request->get('transaction_id'));
                return redirect(route('orders.show',$id));
            }else if($order->payment_gateway == 'razorpay' && $request->get('razorpay_payment_id')){
                $this->orderRepository->updateRazorpayPaymentStatusByGatewayId($request->get('razorpay_payment_id'));
                return redirect(route('orders.show',$id));
            }
        }

        return view('orders.show', compact('background_image','order'));
    }

    /*
     * Get order address and status of delivery for each status
     */
    public function ajaxGetAddressesHtml(Request $request){
        $order = Order::where('user_id',auth()->user()->id)->where('id',$request->order_id)->firstOrFail();
        return view('orders.ajax.addresses',compact('order'));
    }

}

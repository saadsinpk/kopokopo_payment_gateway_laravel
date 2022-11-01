<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Razorpay\Api\Api;

/**
 * Class OrderRepository
 * @package App\Repositories
 * @version July 12, 2022, 12:01 pm UTC
*/

class OrderRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mid',
        'user_id',
        'courier_id',
        'pickup_location',
        'pickup_location_data',
        'save_pickup_location_for_next_order',
        'delivery_locations_data',
        'need_return_to_pickup_location',
        'distance',
        'courier_value',
        'app_value',
        'total_value',
        'customer_observation',
        'offline_payment_method_id',
        'payment_gateway',
        'gateway_id',
        'payment_status',
        'payment_status_date',
        'order_status',
        'order_status_date',
        'status_observation'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }


    /*
     * Find the payment intent on stripe and update the order if necessary
     */
    public function updateStripePaymentStatusByGatewayId($gatewayId)
    {
        $paymentIntent = \Stripe\PaymentIntent::retrieve($gatewayId);
        $orderId = $paymentIntent->metadata->order_id??0;
        $order = Order::find($orderId);
        if($order) {
            if ($paymentIntent->status == 'succeeded') {


                if ($order && $order->payment_status == 'pending') {
                    $order->gateway_id = $paymentIntent->id;
                    $order->payment_status = 'paid';
                    $order->payment_status_date = Carbon::now();
                    if ($order->order_status == 'waiting') {
                        $order->order_status = 'pending';
                        $order->order_status_date = Carbon::now();
                    }
                    $order->save();
                }
            }else {
                if ($order->payment_status == 'paid' && $order->gateway_id == $paymentIntent->id) {
                    $order->payment_status = 'cancelled';
                    $order->payment_status_date = Carbon::now();
                    $order->save();
                }
            }
        }
    }

    /*
     * Refund a specific stripe intent
     */
    public function refundStripePayment($gatewayId)
    {
        $paymentIntent = \Stripe\PaymentIntent::retrieve($gatewayId);
        if($paymentIntent->status == 'succeeded'){
            $paymentIntent->refund();
        }
    }


    /*
     * Find the payment by id on paypal and update the order if necessary
     */
    public function updatePaypalPaymentStatusByGatewayId($gatewayId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, setting('paypal_key').":".setting('paypal_secret'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $result = curl_exec($ch);
        if($result){
            $result = json_decode($result);
            $token = $result->access_token;
            curl_close($ch);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v2/payments/captures/".$gatewayId);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$token
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            if($result) {
                $result = json_decode($result, true);
                if(isset($result['custom_id'])){
                    $orderId = $result['custom_id'];
                    $order = Order::find($orderId);
                    if($result['status'] == 'COMPLETED'){

                        if ($order && $order->payment_status == 'pending') {
                            $order->gateway_id = $gatewayId;
                            $order->payment_status = 'paid';
                            $order->payment_status_date = Carbon::now();
                            if ($order->order_status == 'waiting') {
                                $order->order_status = 'pending';
                                $order->order_status_date = Carbon::now();
                            }
                            $order->save();
                        }
                    }elseif($order && $order->payment_status == 'paid') {
                        $order->gateway_id = $gatewayId;
                        $order->payment_status = 'cancelled';
                        $order->payment_status_date = Carbon::now();
                        $order->save();
                    }
                }
            }
        }
    }

    /*
     * Refund a specific payment on paypal
     */
    public function refundPaypalPayment($gatewayId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, setting('paypal_key').":".setting('paypal_secret'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $result = curl_exec($ch);
        if($result) {
            $result = json_decode($result);
            $token = $result->access_token;
            curl_close($ch);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v2/payments/captures/" . $gatewayId . '/refund');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }

    /*
     * Find the payment by id on mercado pago and update the order if necessary
     */
    public function updateMercadoPagoPaymentStatusByGatewayId($gatewayId)
    {
        \MercadoPago\SDK::setAccessToken(setting('mercado_pago_secret'));
        $payment = \MercadoPago\Payment::get($gatewayId);
        if($payment){
            $orderId = $payment->external_reference;
            $order = Order::find($orderId);
            if($order){
                if($payment->status == 'approved'){
                    if($order->payment_status == 'pending'){
                        $order->gateway_id = $gatewayId;
                        $order->payment_status = 'paid';
                        $order->payment_status_date = Carbon::now();
                        if($order->order_status == 'waiting'){
                            $order->order_status = 'pending';
                            $order->order_status_date = Carbon::now();
                        }
                        $order->save();
                    }
                }elseif($order->payment_status == 'paid'){
                    $order->gateway_id = $gatewayId;
                    $order->payment_status = 'cancelled';
                    $order->payment_status_date = Carbon::now();
                    $order->save();
                }
            }
        }
    }

    /*
    * Find the payment by id on Flutterwave and update the order if necessary
    */
   public function updateFlutterwavePaymentStatusByGatewayId($gatewayId)
   {
       $response = Http::withToken(setting('flutterwave_secret'))->get('https://api.flutterwave.com/v3/transactions/' . $gatewayId . '/verify')->json();
       if ($response['status'] == 'success') {
           $orderId = $response['data']['meta']['order_id'];
           $order = Order::find($orderId);
           if ($order) {
               if ($response['data']['status'] == "successful") {
                   if ($response['data']['amount'] == $order->total_value && $order->payment_status == 'pending') {
                       $order->gateway_id = $gatewayId;
                       $order->payment_status = 'paid';
                       $order->payment_status_date = Carbon::now();
                       if ($order->order_status == 'waiting') {
                           $order->order_status = 'pending';
                           $order->order_status_date = Carbon::now();
                       }
                       $order->save();
                   }
               } else {
                   if ($order->payment_status == 'paid' && $order->gateway_id == $gatewayId) {
                       $order->payment_status = 'cancelled';
                       $order->payment_status_date = Carbon::now();
                       $order->save();
                   }
               }
           }
       }
   }

   /*
   * Find the payment by id on Razorpay and update the order if necessary
   */
    public function updateRazorpayPaymentStatusByGatewayId($paymentId)
    {
        $api = new Api(setting('razorpay_key'), setting('razorpay_secret'));
        $payment = $api->payment->fetch($paymentId);
        if (isset($payment)) {
            $orderId = $payment->notes['order_id'];
            $order = Order::find($orderId);
            if ($order) {
                if ($payment->status == "captured") {
                    if ($payment->amount == intval($order->total_value * 100) && $order->payment_status == 'pending') {
                        $order->gateway_id = $paymentId;
                        $order->payment_status = 'paid';
                        $order->payment_status_date = Carbon::now();
                        if ($order->order_status == 'waiting') {
                            $order->order_status = 'pending';
                            $order->order_status_date = Carbon::now();
                        }
                        $order->save();
                    }
                } else {
                    if ($order->payment_status == 'paid' && $order->gateway_id == $paymentId) {
                        $order->payment_status = 'cancelled';
                        $order->payment_status_date = Carbon::now();
                        $order->save();
                    }
                }
            }
        }
    }

    public function updateKopoKopoPaymentStatusByGatewayId($paymentId)
    {
        $api = new Api(setting('kopokopo_key'), setting('kopokopo_secret'));
        $payment = $api->payment->fetch($paymentId);
        if (isset($payment)) {
            $orderId = $payment->notes['order_id'];
            $order = Order::find($orderId);
            if ($order) {
                if ($payment->status == "captured") {
                    if ($payment->amount == intval($order->total_value * 100) && $order->payment_status == 'pending') {
                        $order->gateway_id = $paymentId;
                        $order->payment_status = 'paid';
                        $order->payment_status_date = Carbon::now();
                        if ($order->order_status == 'waiting') {
                            $order->order_status = 'pending';
                            $order->order_status_date = Carbon::now();
                        }
                        $order->save();
                    }
                } else {
                    if ($order->payment_status == 'paid' && $order->gateway_id == $paymentId) {
                        $order->payment_status = 'cancelled';
                        $order->payment_status_date = Carbon::now();
                        $order->save();
                    }
                }
            }
        }
    }


    /*
    * Refund a specific payment on Flutterwave
    */
    public function refundFlutterwavePayment($gatewayId, $amount)
    {
        Http::withToken(setting('flutterwave_secret'))->post('https://api.flutterwave.com/v3/transactions/' . $gatewayId . '/refund',[
            'amount' => $amount,
        ])->json();
    }

    /*
    * Refund a specific payment on Razorpay
    */
    public function refundRazorpayPayment($gatewayId)
    {
        $api = new Api(setting('razorpay_key'), setting('razorpay_secret'));
        $api->payment->fetch($gatewayId)->refund(array("speed"=>"optimum"));
    }
    
    public function refundKopoKopoPayment($gatewayId)
    {
        $api = new Api(setting('kopokopo_key'), setting('kopokopo_secret'));
        $api->payment->fetch($gatewayId)->refund(array("speed"=>"optimum"));
    }


    /*
     * Refund a specific payment on mercado pago
     */
    public function refundMercadoPagoPayment($gatewayId)
    {
        \MercadoPago\SDK::setAccessToken(setting('mercado_pago_secret'));
        $payment = \MercadoPago\Payment::get($gatewayId);
        if($payment){
            $payment->refund();
        }
    }

    public function refundOrderPayment(Order $order){
        if (!$order->offline_payment_method_id && isset($order->gateway_id)) {
            //Refund the order based on the payment method
            if ($order->payment_gateway == 'stripe') {
                $this->orderRepository->refundStripePayment($order->gateway_id);
                $order->payment_status = "cancelled";
                $order->payment_status_date = Carbon::now();
            } elseif ($order->payment_gateway == 'paypal') {
                $this->orderRepository->refundPaypalPayment($order->gateway_id);
                $order->payment_status = "cancelled";
                $order->payment_status_date = Carbon::now();
            } elseif ($order->payment_gateway == 'mercado_pago') {
                $this->orderRepository->refundMercadoPagoPayment($order->gateway_id);
                $order->payment_status = "cancelled";
                $order->payment_status_date = Carbon::now();
            } elseif ($order->payment_gateway == 'flutterwave') {
                $this->orderRepository->refundFlutterwavePayment($order->gateway_id, $order->total_value);
                $order->payment_status = "cancelled";
                $order->payment_status_date = Carbon::now();
            } elseif ($order->payment_gateway == 'razorpay') {
                $this->orderRepository->refundRazorpayPayment($order->gateway_id);
                $order->payment_status = "cancelled";
                $order->payment_status_date = Carbon::now();
            } elseif ($order->payment_gateway == 'razorpay') {
                $this->orderRepository->refundRazorpayPayment($order->gateway_id);
                $order->payment_status = "cancelled";
                $order->payment_status_date = Carbon::now();
            }
            
        }
    }

}

<?php

namespace App\Http\Controllers\API;

use App\Repositories\OrderRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookAPIController extends Controller
{
    private $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    public function stripeWebhook(Request $request)
    {
        switch ($request->data['object']['object'] ?? null) {
            case 'payment_intent':
                $id = $request->data['object']['id'];
                $this->orderRepository->updateStripePaymentStatusByGatewayId($id);
                break;
        }
    }

    public function paypalWebhook(Request $request)
    {
        switch ($request->event_type ?? null) {
            case 'PAYMENT.CAPTURE.COMPLETED':
                $id = $request->resource['id'];
                $this->orderRepository->updatePaypalPaymentStatusByGatewayId($id);
                break;
        }
    }

    public function mercadoPagoWebhook(Request $request)
    {
        switch ($request->event_type ?? null) {
            case 'payment':
                $id = $request->resource->id;
                $this->orderRepository->updateMercadoPagoPaymentStatusByGatewayId($id);
                break;
        }
    }

    public function flutterwaveWebhook(Request $request)
    {
        switch ($request->{"event.type"} ?? null) {
            case 'CARD_TRANSACTION':
                $id = $request->id;
                $this->orderRepository->updateFlutterwavePaymentStatusByGatewayId($id);
                break;
        }
    }

    public function razorpayWebhook(Request $request)
    {
        switch ($request->event ?? null) {
            case 'order.paid':
                $id = $request->payload['payment']['entity']['id'];
                $this->orderRepository->updateRazorpayPaymentStatusByGatewayId($id);
                break;
        }
    }

    
    public function kopoKopoWebhook(Request $request)
    {
        switch ($request->event ?? null) {
            case 'order.paid':
                $id = $request->payload['payment']['entity']['id'];
                $this->orderRepository->updateKopoKopoPaymentStatusByGatewayId($id);
                break;
        }
    }
}

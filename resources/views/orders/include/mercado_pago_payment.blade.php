<div class="row">
    <div class="col-12 text-center">
        <h5 style="color: #777777">
            {{ __('Payment Pending') }}<br>
            <small>{{ number_format($order->distance, 3, '.', '') }} {{ setting('distance_unit', 'mi') }} -
                {!! getPrice($order->total_value) !!}</small>
        </h5>
        <div id="payment-container" style="margin: 0 auto; max-width: 500px"></div>

        <div id="payment-message" class="hidden"></div>

        <span style="font-size: 12px"><i class="fa fa-exclamation-circle" style="color: #ff0000"></i>
            {{ __('The order is only send for the courier after the payment is completed') }}</span>
        <br><br>
    </div>
</div>
@push('css')
    <style type="text/css">
        #payment-container {
            width: 30vw;
            min-width: 500px;
            margin: 0 auto;
            align-self: center;
            padding: 40px;
        }

        @media only screen and (max-width: 600px) {
            #payment-container {
                width: 80vw;
                min-width: initial;
            }
        }
    </style>
@endpush
@push('scripts')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        initialize();


        async function initialize() {
            const mp = new MercadoPago('{{ setting('mercado_pago_key') }}');
            const {
                preference
            } = await fetch("{{ url('api/orders/initializePayment') }}", {
                method: "POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: "{{ $order->id }}",
                    api_token: "{{ auth()->user()->api_token }}"
                }),
            }).then((r) => r.json());

            mp.checkout({
                preference: {
                    id: preference
                },
                render: {
                    container: '#payment-container'
                }
            });
        }
    </script>
@endpush

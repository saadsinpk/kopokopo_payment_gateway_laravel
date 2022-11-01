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
    <script
        src="https://www.paypal.com/sdk/js?client-id={{ setting('paypal_key') }}&currency={{ setting('currency', 'USD') }}">
    </script>
    <script>
        paypal.Buttons({
            createOrder: (data, actions) => {
                return actions.order.create({
                    purchase_units: [{
                        custom_id: '{{ $order->id }}',
                        amount: {
                            currency_code: "{{ setting('currency', 'USD') }}",
                            value: '{{ $order->total_value }}'
                        }
                    }]
                });
            },
            onApprove: (data, actions) => {
                return actions.order.capture().then(function(orderData) {
                    // Successful capture! For dev/demo purposes:
                    const transaction = orderData.purchase_units[0].payments.captures[0];
                    //alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                    //console.log(orderData.id);
                    return window.location.href =
                        "{{ route('orders.show', $order->id) }}?payment_gateway_id=" + transaction.id;
                });
            }
        }).render('#payment-container');
    </script>
@endpush

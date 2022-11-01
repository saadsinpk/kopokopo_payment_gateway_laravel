<body class="@if (setting('language_rtl', false)) text-right @endif"
    dir="{{ setting('language_rtl', false) ? 'rtl' : 'ltr' }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Template CSS -->
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <div class="row mx-1 vertical-center">
        <div class="col-12 text-center">
            <h5 style="color: #777777">
                {{ __('Payment Pending') }}<br>
                <small>{{ number_format($order->distance, 3, '.', '') }} {{ setting('distance_unit', 'mi') }} -
                    {!! getPrice($order->total_value) !!}</small>
            </h5>
            <button type="button" id="start-payment-button" onclick="makePayment()">{{ __('Pay Now') }}</button>
            <div id="payment-container" style="margin: 0 auto;"></div>
            <div id="payment-message" class="hidden"></div>
        </div>
    </div>
    <style>
        .vertical-center {
            min-height: 100%;
            min-height: 100vh;

            display: flex;
            align-items: center;
        }
    </style>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        makePayment();

        function makePayment() {
            FlutterwaveCheckout({
                public_key: "{{ setting('flutterwave_key') }}",
                tx_ref: "{{ $order->id }}-{{ Str::random(10) }}",
                amount: {{ number_format($order->total_value, 2, '.', '') }},
                currency: "{{ setting('currency', 'USD') }}",
                meta: {
                    order_id: '{{ $order->id }}',
                },
                customer: {
                    email: "{{ $order->user->email }}",
                    phone_number: "{{ $order->user->phone }}",
                    name: "{{ $order->user->name }}",
                },
                customizations: {
                    title: "{{ setting('APP_NAME') }}",
                    description: "{{ __('Payment of order') . ' ' . $order->id }}",
                    logo: " {{ $appLogo }}",
                },
            });
        }
    </script>
</body>

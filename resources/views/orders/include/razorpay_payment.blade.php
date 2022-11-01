 <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
 <meta name="csrf-token" content="{{ csrf_token() }}">
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
         <button id="start-payment-button" class="btn btn-sm btn-outline-primary btn-block mb-3"
             target="_blank">{{ __('Pay Now') }}</button>
         <div id="payment-container" style="margin: 0 auto;"></div>
         <div id="payment-message" class="hidden"></div>
     </div>
 </div>
 <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
 <script>
     initialize();


     async function initialize() {
         const {
             payment_order_id
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

         var options = {
             "key": "{{ setting('razorpay_key') }}",
             "amount": "{{ intval($order->total_value * 100) }}",
             "currency": "INR",
             "name": "{{ setting('APP_NAME') }}",
             "description": "{{ __('Payment of order') . ' ' . $order->id }}",
             "image": "{{ $app_logo }}",
             "callback_url": "{{ route('orders.show', $order->id) }}",
             "prefill": {
                 "name": "{{ $order->user->name }}",
                 "email": "{{ $order->user->email }}",
             },
             "order_id": payment_order_id,
             "notes": {
                 "order_id": '{{ $order->id }}',
             },
             "theme": {
                 "color": "#6777ef"
             }
         };
         var rzp1 = new Razorpay(options);
         rzp1.open();
         document.getElementById('start-payment-button').onclick = function(e) {
             rzp1.open();
             e.preventDefault();
         }
     }
 </script>

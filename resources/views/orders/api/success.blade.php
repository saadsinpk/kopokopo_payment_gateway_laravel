 <body class="@if (setting('language_rtl', false)) text-right @endif"
     dir="{{ setting('language_rtl', false) ? 'rtl' : 'ltr' }}">
     <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
     <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
     <div dir="{{ setting('language_rtl', false) ? 'rtl' : 'ltr' }}" class="row mx-1 vertical-center">
         <div class="card text-white bg-success mb-3" style="width: 99%;">
             <div class="card-header">{{ __('Payment of order') . ' ' . $order->id }}</div>
             <div class="card-body">
                 <h5 class="card-title">{{ __('Payment Success') }}</h5>
                 <p class="card-text">{{ __('Go back to the app.') }}</p>
             </div>
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
 </body>

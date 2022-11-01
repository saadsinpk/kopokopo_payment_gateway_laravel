<div class="row" id="divPaymentMethods">
    @foreach(($offlinePaymentMethods??[]) as $offlinePaymentMethod)
        <div class="col-md-3 pb-2">
            <div class="card m-0 text-center selectedPaymentMethod p-2" data-type="offline" data-id="{{$offlinePaymentMethod->id}}" style="cursor:pointer;height: 120px!important;">
                <center>
                    <img src="{{$offlinePaymentMethod->getFirstMediaUrl('default','thumb')??asset('img/default_logo.jpeg')}}" title="{{$offlinePaymentMethod->name}}" style="height: 50px; width: 50px"><br>
                    <h6 style="font-size: 0.9em;padding-top: 20px;">{{$offlinePaymentMethod->name}}</h6>
                </center>
            </div>
        </div>
    @endforeach
    @if(setting('enable_stripe'))
        <div class="col-md-3 pb-2">
            <div class="card m-0 text-center selectedPaymentMethod p-2" data-type="online" data-id="stripe" style="cursor:pointer;height: 120px!important;">
                <center>
                    <img src="{{asset('img/stripe.png')}}" title="{{__('Secure Online Payment')}}" style="height: 50px; "><br>
                    <h6 style="font-size: 0.9em;padding-top: 20px;">{{__('Online Payment')}}</h6>
                </center>
            </div>
        </div>
    @endif
    @if(setting('enable_paypal'))
        <div class="col-md-3 pb-2">
            <div class="card m-0 text-center selectedPaymentMethod p-2" data-type="online" data-id="paypal" style="cursor:pointer;height: 120px!important;">
                <center>
                    <img src="{{asset('img/paypal.svg')}}" title="{{__('Pay with Paypal')}}" style="height: 50px; "><br>
                    <h6 style="font-size: 0.9em;padding-top: 20px;">{{__('Paypal')}}</h6>
                </center>
            </div>
        </div>
    @endif
    @if(setting('enable_mercado_pago'))
        <div class="col-md-3 pb-2">
            <div class="card m-0 text-center selectedPaymentMethod p-2" data-type="online" data-id="mercado_pago" style="cursor:pointer;height: 120px!important;">
                <center>
                    <img src="{{asset('img/mercado-pago.png')}}" title="{{__('Pay with Mercado Pago')}}" style="height: 50px; "><br>
                    <h6 style="font-size: 0.9em;padding-top: 20px;">{{__('Mercado Pago')}}</h6>
                </center>
            </div>
        </div>
    @endif
    @if(setting('enable_flutterwave'))
        <div class="col-md-3 pb-2">
            <div class="card m-0 text-center selectedPaymentMethod p-2" data-type="online" data-id="flutterwave" style="cursor:pointer;height: 120px!important;">
                <center>
                    <img src="{{asset('img/flutterwave-logo.png')}}" title="{{__('Pay with Flutterwave')}}" style="height: 50px; "><br>
                    <h6 style="font-size: 0.9em;padding-top: 20px;">{{__('Flutterwave')}}</h6>
                </center>
            </div>
        </div>
    @endif
    @if(setting('enable_razorpay'))
        <div class="col-md-3 pb-2">
            <div class="card m-0 text-center selectedPaymentMethod p-2" data-type="online" data-id="razorpay" style="cursor:pointer;height: 120px!important;">
                <center>
                    <img src="{{asset('img/razorpay-logo.png')}}" title="{{__('Pay with Razorpay')}}" style="height: 50px;"><br>
                    <h6 style="font-size: 0.9em;padding-top: 20px;">{{__('Razorpay')}}</h6>
                </center>
            </div>
        </div>
    @endif
</div>

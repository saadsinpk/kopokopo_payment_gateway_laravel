@extends('layouts.app')
@section('title')
    {{ __('Payments API Settings') }}
@endsection
@push('page_css')
    <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
@endpush
@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-md-4">
                    @include('layouts.admin.settings.sidebar')
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <h4>{{ __('Payments API Settings') }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => route('admin.settings.saveSettings'), 'method' => 'patch','enctype' => 'multipart/form-data']) !!}
                                @include('flash::message')
                                @include('stisla-templates::common.errors')
                                <h5 class="col-12 pb-4"><i class="mr-4 fab fa-stripe"></i> {!! __('Stripe') !!}</h5>

                                <!-- 'Boolean enable_stripe Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_stripe', __('Enable Stripe'),['class' => 'col-2 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_stripe', 0) !!}
                                            {!! Form::checkbox('enable_stripe', 1, setting('enable_stripe', false)) !!}
                                            <span class="ml-2">{!! trans('Check to enable stripe payment API') !!}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- stripe_app_id Field -->
                                <div class="form-group row">
                                    {!! Form::label('stripe_key', __('Stripe Key'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('stripe_key', setting('stripe_key'),  ['class' => 'form-control','placeholder'=>  __('Input your Stripe key')]) !!}
                                        @endif
                                    </div>
                                </div>

                                <!-- stripe_app_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('stripe_secret', trans('Stripe Secret'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('stripe_secret', setting('stripe_secret'),  ['class' => 'form-control','placeholder'=>  __('Input your Stripe Secret')]) !!}
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('stripe_webhook_url', trans('Stripe WebHook URL'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{url()->to('api/webhook/stripe')}}" disabled="disabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary btnCopyField" type="button"><i class="fas fa-copy"></i></button>
                                            </div>
                                            <span class="text-muted">{{__('Paste it on webhook URL to receive order updates from Stripe')}}</span>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="col-12 pb-4"><i class="mr-4 fab fa-paypal"></i> {!! __('Paypal') !!}</h5>

                                <!-- 'Boolean enable_paypal Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_paypal', __('Enable Paypal'),['class' => 'col-2 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_paypal', 0) !!}
                                            {!! Form::checkbox('enable_paypal', 1, setting('enable_paypal', false)) !!}
                                            <span class="ml-2">{!! trans('Check to enable Paypal payment API') !!}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- paypal_key Field -->
                                <div class="form-group row">
                                    {!! Form::label('paypal_key', __('Paypal Client Id'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('paypal_key', setting('paypal_key'),  ['class' => 'form-control','placeholder'=>  __('Input your Paypal Client Id')]) !!}
                                        @endif
                                    </div>
                                </div>

                                <!-- paypal_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('paypal_secret', trans('Paypal Secret'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('paypal_secret', setting('paypal_secret'),  ['class' => 'form-control','placeholder'=>  __('Input your Paypal Secret')]) !!}
                                        @endif
                                    </div>
                                </div>
                                <!-- paypal_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('paypal_webhook_url', trans('Paypal WebHook URL'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{url()->to('api/webhook/paypal')}}" disabled="disabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary btnCopyField" type="button"><i class="fas fa-copy"></i></button>
                                            </div>
                                            <span class="text-muted">{{__('Paste it on webhook URL to receive order updates from Paypal')}}</span>
                                        </div>

                                    </div>
                                </div>

                                <hr>
                                <h5 class="col-12 pb-4"><i class="mr-4 fas fa-handshake"></i> {!! __('Mercado Pago') !!}</h5>

                                <!-- 'Boolean enable_stripe Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_mercado_pago', __('Enable Mercado Pago'),['class' => 'col-2 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_mercado_pago', 0) !!}
                                            {!! Form::checkbox('enable_mercado_pago', 1, setting('enable_mercado_pago', false)) !!}
                                            <span class="ml-2">{!! trans('Check to enable Mercado Pago payment API') !!}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- paypal_key Field -->
                                <div class="form-group row">
                                    {!! Form::label('mercado_pago_key', __('Mercado Pago Public Key'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                           {!! Form::text('mercado_pago_key', setting('mercado_pago_key'),  ['class' => 'form-control','placeholder'=>  __('Input your Mercado Pago Client Id')]) !!}
                                        @endif
                                    </div>
                                </div>

                                <!-- paypal_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('mercado_pago_secret', trans('Mercado Pago Access Token'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('mercado_pago_secret', setting('mercado_pago_secret'),  ['class' => 'form-control','placeholder'=>  __('Input your Mercado Pago Secret')]) !!}
                                        @endif
                                    </div>
                                </div>
                                <!-- paypal_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('mercadopago_webhook_url', trans('Mercado Pago WebHook URL'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{url()->to('api/webhook/mercadopago')}}" disabled="disabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary btnCopyField" type="button"><i class="fas fa-copy"></i></button>
                                            </div>
                                            <span class="text-muted">{{__('Paste it on webhook URL to receive order updates from Mercado Pago')}}</span>
                                        </div>

                                    </div>
                                </div>

                                <hr>
                                <h5 class="col-12 pb-4"><i class="mr-4 fas fa-handshake"></i> {!! __('Flutterwave') !!}</h5>

                                <!-- 'Boolean enable_flutterwave Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_flutterwave', __('Enable Flutterwave'),['class' => 'col-2 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_flutterwave', 0) !!}
                                            {!! Form::checkbox('enable_flutterwave', 1, setting('enable_flutterwave', false)) !!}
                                            <span class="ml-2">{!! trans('Check to enable Flutterwave payment API') !!}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- flutterwave_key Field -->
                                <div class="form-group row">
                                    {!! Form::label('flutterwave_key', __('Flutterwave Public Key'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                           {!! Form::text('flutterwave_key', setting('flutterwave_key'),  ['class' => 'form-control','placeholder'=>  __('Input your Flutterwave Public Key')]) !!}
                                        @endif
                                    </div>
                                </div>

                                <!-- flutterwave_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('flutterwave_secret', trans('Flutterwave Secret'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('flutterwave_secret', setting('flutterwave_secret'),  ['class' => 'form-control','placeholder'=>  __('Input your Flutterwave Secret')]) !!}
                                        @endif
                                    </div>
                                </div>
                                <!-- flutterwave_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('mercadopago_webhook_url', trans('Flutterwave WebHook URL'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{url()->to('api/webhook/flutterwave')}}" disabled="disabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary btnCopyField" type="button"><i class="fas fa-copy"></i></button>
                                            </div>
                                            <span class="text-muted">{{__('Paste it on webhook URL to receive order updates from Mercado Pago')}}</span>
                                        </div>

                                    </div>
                                </div>

                                <hr>
                                <h5 class="col-12 pb-4"><i class="mr-4 fas fa-handshake"></i> {!! __('Razorpay') !!}</h5>

                                <!-- 'Boolean enable_razorpay Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_razorpay', __('Enable Razorpay'),['class' => 'col-2 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_razorpay', 0) !!}
                                            {!! Form::checkbox('enable_razorpay', 1, setting('enable_razorpay', false)) !!}
                                            <span class="ml-2">{!! trans('Check to enable Razorpay payment API') !!}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- razorpay_key Field -->
                                <div class="form-group row">
                                    {!! Form::label('razorpay_key', __('Razorpay Client Id'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('razorpay_key', setting('razorpay_key'),  ['class' => 'form-control','placeholder'=>  __('Input your Razorpay Client Id')]) !!}
                                        @endif
                                    </div>
                                </div>

                                <!-- razorpay_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('razorpay_secret', trans('Razorpay Secret'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('razorpay_secret', setting('razorpay_secret'),  ['class' => 'form-control','placeholder'=>  __('Input your Razorpay Secret')]) !!}
                                        @endif
                                    </div>
                                </div>
                                <!-- razorpay_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('razorpay_webhook_url', trans('Razorpay WebHook URL'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{url()->to('api/webhook/razorpay')}}" disabled="disabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary btnCopyField" type="button"><i class="fas fa-copy"></i></button>
                                            </div>
                                            <span class="text-muted">{{__('Paste it on webhook URL to receive order updates from Razorpay')}}</span>
                                        </div>

                                    </div>
                                </div>

                                <hr>
                                <h5 class="col-12 pb-4"><i class="mr-4 fas fa-handshake"></i> {!! __('Kopo Kopo') !!}</h5>

                                <!-- 'Boolean enable_razorpay Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_kopokopo', __('Enable kopokopo'),['class' => 'col-2 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_kopokopo', 0) !!}
                                            {!! Form::checkbox('enable_kopokopo', 1, setting('enable_kopokopo', false)) !!}
                                            <span class="ml-2">{!! trans('Check to enable KopoKopo payment API') !!}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- razorpay_key Field -->
                                <div class="form-group row">
                                    {!! Form::label('kopokopo_key', __('kopokopo Client Id'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('kopokopo_key', setting('kopokopo_key'),  ['class' => 'form-control','placeholder'=>  __('Input your KopoKopo Client Id')]) !!}
                                        @endif
                                    </div>
                                </div>

                                <!-- razorpay_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('kopokopo_secret', trans('kopokopo Secret'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('kopokopo_secret', setting('kopokopo_secret'),  ['class' => 'form-control','placeholder'=>  __('Input your KopoKopo Secret')]) !!}
                                        @endif
                                    </div>
                                </div>
                                <!-- razorpay_secret Field -->
                                <div class="form-group row">
                                    {!! Form::label('kopokopo_webhook_url', trans('kopokopo WebHook URL'), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{url()->to('api/webhook/kopokopo')}}" disabled="disabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary btnCopyField" type="button"><i class="fas fa-copy"></i></button>
                                            </div>
                                            <span class="text-muted">{{__('Paste it on webhook URL to receive order updates from KopoKopo')}}</span>
                                        </div>

                                    </div>
                                </div>


                                <div class="form-group text-center pt-4">
                                    {!! Form::button(__('Save'), ['type' => 'submit','class' => 'btn btn-primary']) !!}
                                    <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.btnCopyField').click(function () {
                let $this = $(this);
                $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                let area = $(this).parent('div').children('input');
                area.focus();
                area.select();

                document.execCommand("copy");
                navigator.clipboard.writeText(area.val());

                $(this).html('<i class="fas fa-check"></i>');

                setTimeout(function(){
                    $this.html('<i class="fas fa-copy"></i>');
                },1000);
            });
        });
    </script>
@endpush

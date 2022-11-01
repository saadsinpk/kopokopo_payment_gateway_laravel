<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('date', __('Date') . ':') !!}
            <p>{{ $order->created_at->format('l j F Y H:i:s') }}</p>
        </div>
        <!-- Courier Id Field -->
        <div class="form-group">
            {!! Form::label('courier_id', __('Driver') . ':') !!}
            <p>
                {{ __('Name') . ': ' . $order->courier->user->name }}<br>
                {{ __('Phone') . ': ' . $order->courier->user->phone }}<br>
                {{ __('Email') . ': ' . $order->courier->user->email }}<br>
            </p>
        </div>
        <!-- Customer Id Field -->
        <div class="form-group">
            {!! Form::label('user_id', __('Customer:')) !!}
            <p>
                {{ __('Name') . ': ' . $order->user->name }}<br>
                {{ __('Phone') . ': ' . $order->user->phone }}<br>
                {{ __('Email') . ': ' . $order->user->email }}<br>
            </p>
        </div>

    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-sm-3">
                <!-- Distance Field -->
                <div class="form-group">
                    {!! Form::label('distance', __('Distance') . ':') !!}
                    <p>{{ $order->distance }} {{ setting('distance_unit', 'mi') }}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <!-- Courier Value Field -->
                <div class="form-group">
                    {!! Form::label('courier_value', __('Courier value') . ':') !!}
                    <p>{!! getPrice($order->courier_value) !!}</p>
                </div>

            </div>
            <div class="col-sm-3">
                <!-- App Value Field -->
                <div class="form-group">
                    {!! Form::label('app_value', __('App value') . ':') !!}
                    <p>{!! getPrice($order->app_value) !!}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <!-- Total Value Field -->
                <div class="form-group">
                    {!! Form::label('total_value', __('Total') . ':') !!}
                    <p>{!! getPrice($order->total_value) !!}</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <!-- Order Status Field -->
                <div class="form-group">
                    {!! Form::label('order_status', __('Order status:')) !!}
                    <p>{{ trans('general.order_status_list.' . $order->order_status) }}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Order Status Date Field -->
                <div class="form-group">
                    {!! Form::label('order_status_date', __('Order status date:')) !!}
                    <p>{{ $order->order_status_date->translatedFormat('j F Y H:i:s') }}</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4">
                <!-- Payment Status Field -->
                <div class="form-group">
                    {!! Form::label('payment_status', __('Payment status:')) !!}
                    <p>{{ trans('general.order_status_list.' . $order->payment_status) }}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <!-- Payment Status Date Field -->
                <div class="form-group">
                    {!! Form::label('payment_status_date', __('Payment status date:')) !!}
                    <p>{{ $order->payment_status_date->translatedFormat('j F Y H:i:s') }}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <!-- Payment Method Field -->
                <div class="form-group">
                    {!! Form::label('payment_method', __('Payment method:')) !!}
                    <p>
                        @if ($order->offline_payment_method_id != 0)
                            {{ $order->offlinePaymentMethod->name }}
                        @else
                            {{ $order->payment_gateway }} ({{ $order->gateway_id }})
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="adressesContainer">

</div>
<div class="row">
    <div class="col-md-6">
        <!-- Need Return To Pickup Location Field -->
        <div class="form-group">
            {!! Form::label('need_return_to_pickup_location', __('Need return to pickup location:')) !!}
            <p>{!! getBoolColumn($order->need_return_to_pickup_location) !!}</p>
        </div>
        @if (!empty($order->customer_observation))
            <!-- Customer Observation Field -->
            <div class="form-group">
                {!! Form::label('customer_observation', __('Customer Observation') . ':') !!}
                <p>{{ $order->customer_observation }}</p>
            </div>
        @endif
    </div>
    <div class="col-md-6" id="mapsHere" style="min-height: 300px">

    </div>
</div>

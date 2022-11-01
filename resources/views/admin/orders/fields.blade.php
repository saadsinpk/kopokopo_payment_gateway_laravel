
<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', __('Customer').':') !!}
    {!! Form::select('user_id',isset($customer) ? [$customer] : [], null, ['id' => 'customer_id', 'class' => 'form-control']) !!}
</div>

<!-- Courier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('courier_id', __('Courier').':') !!}
    {!! Form::select('courier_id',isset($courier) ? [$courier] : [], null, ['id' => 'courier_id', 'class' => 'form-control']) !!}
</div>

<!-- Pickup Location Field -->
<div class="form-group col-sm-12">
    <input type="hidden" name="collect_address_data" value='{{$order->pickup_location_data}}'>
    {!! Form::label('pickup_location', __('Pickup Location').':') !!}
    {!! Form::text('pickup_location', null, ['class' => 'form-control','id' => 'inputCollectPlace','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Save Pickup Location For Next Order Field -->
<div class="form-group col-sm-12" style="">
    <div class="form-check">
        <input type="hidden" name="save_pickup_location_for_next_order" value="0">
        {!! Form::checkbox('save_pickup_location_for_next_order', '1', null, ['id' => 'save_pickup_location_for_next_order']) !!}
        <label class="form-check-label" for="save_pickup_location_for_next_order">
           {{__('Save pickup location for next order')}}
        </label>
    </div>
</div>
<div class="form-group col-sm-12">
{!! Form::label('delivery_locations', __('Delivery Locations').':') !!}
    <div id="addressesList">
        @foreach(json_decode($order->delivery_locations_data) as $k => $deliveryLocationData)
            <div class="row mb-1">
                <div class="col-lg-11 col-10 mr-0 pr-0">
                    <input type="hidden" name="delivery_address_data[]" value='{!! json_encode($deliveryLocationData) !!}'>
                    <input type="text" class="form-control" disabled="true" name="delivery_place[]" value="{{$deliveryLocationData->formatted_address}}" style="height:30px;border-bottom-right-radius: 0px;border-top-right-radius: 0px;">
                    <div class="invalid-feedback" data-name="delivery_place">
                    </div>
                </div>
                <div class="col-lg-1 col-2 ml-0 pl-0">
                    <button type="button" class="btn btn-outline-danger btn-block btnRemoveAddress" style="height:30px;line-height:20px;border-bottom-left-radius: 0px;border-top-left-radius: 0px;">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row mb-0 pb-0">
        <div class="col-lg-11 col-10 mr-0 pr-0">
            <input type="hidden" name="delivery_address_tmp" value="">
            <input type="text" class="form-control" id="inputDeliveryPlace" tabindex="-1" name="delivery_address" placeholder="{{__('Enter and select the street, neighborhood and city of the delivery location')}}" tabindex="1" value="{{ (Cookie::get('delivery_place') !== null) ? Cookie::get('delivery_place') : old('delivery_place') }}" style="border-bottom-right-radius: 0px;border-top-right-radius: 0px;">
            <div class="invalid-feedback" data-name="delivery_place">
            </div>
        </div>
        <div class="col-lg-1 col-2 ml-0 pl-0">
            <button type="button" class="btn btn-primary btn-block h-100 btnAddAddress" style="border-bottom-left-radius: 0px;border-top-left-radius: 0px;">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="row mt-0 pt-0">
        <div class="col-12 text-right">
            {{__('Press + to add more delivery locations')}}
        </div>
    </div>
</div>
<!-- Need Return To Pickup Location Field -->
<div class="form-group col-sm-12" style="">
    <div class="form-check">
        <input type="hidden" name="need_return_to_pickup_location" value="0">
        {!! Form::checkbox('need_return_to_pickup_location', '1', null, ['id' => 'need_return_to_pickup_location']) !!}
        <label class="form-check-label" for="need_return_to_pickup_location">
           {{__('Need return to pickup location')}}
        </label>
    </div>
</div>

<!-- Distance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('distance', __('Distance').':') !!}
    {!! Form::number('distance', null, ['class' => 'form-control','step' => 0.001,'min' =>0]) !!}
</div>

<!-- Courier Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('courier_value', __('Courier Value').':') !!}
    {!! Form::number('courier_value', null, ['class' => 'form-control','step' => 0.01,'min' =>0]) !!}
</div>

<!-- App Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('app_value', __('App Value').':') !!}
    {!! Form::number('app_value', null, ['class' => 'form-control','step' => 0.01,'min' =>0]) !!}
</div>

<!-- Customer Observation Field -->
<div class="form-group col-sm-12">
    {!! Form::label('customer_observation', __('Customer Observation').':') !!}
    {!! Form::textarea('customer_observation', null, ['class' => 'form-control','style' => 'min-height:100px']) !!}
</div>
<!-- Offline Payment Method Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('offline_payment_method_id', __('Offline Payment Method').':') !!}
    {!! Form::select('offline_payment_method_id',[0 => 'None']+$offlinePaymentMethods->toArray(), null, ['class' => 'form-control select2']) !!}
</div>

<!-- Payment Gateway Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_gateway', __('Payment Gateway').':') !!}
    {!! Form::select('payment_gateway', ['' => 'None']+getAvailablePaymentGatewaysArray(),null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>
<!-- Payment Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_status', __('Payment Status').':') !!}
    {!! Form::select('payment_status',getAvailablePaymentStatusArray(), null, ['class' => 'form-control select2']) !!}
</div>

<!-- Order Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order_status', __('Order Status').':') !!}
    {!! Form::select('order_status',getAvailableOrderStatusArray(), null, ['class' => 'form-control select']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.orders.index') }}" class="btn btn-light">{{__('crud.cancel')}}</a>
</div>
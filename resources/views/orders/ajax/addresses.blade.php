<div class="col-md-{{$order->need_return_to_pickup_location?4:6}}">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <b><i class="fas fa-home"></i> {{__('Pickup location')}}</b>
            </h5>
            <div class="card-text">
                <p>
                    @php($pickupLocationData = json_decode($order->pickup_location_data,true))
                    @if(in_array($order->order_status,["waiting","pending","accepted"]))
                        <i class="fa fa-clock text-warning" title="{{__('Waiting')}}"></i>
                    @elseif(in_array($order->order_status,["collected","delivered","completed"]))
                        <i class="fa fa-check-circle text-success" title="{{__('Collected')}}"></i>
                    @else
                        <i class="fa fa-times-circle text-danger" title="{{__('Cancelled')}}"></i>
                    @endif
                    {{$pickupLocationData['formatted_address']." - ".$pickupLocationData['number']??'-'}}
                </p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-{{$order->need_return_to_pickup_location?4:6}}">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <b><i class="fas fa-map"></i>
                    @php($deliveryPlacesArray = json_decode($order->delivery_locations_data,true))
                    @if(count($deliveryPlacesArray) > 1)
                        {{__('Delivery places')}}
                    @else
                        {{__('Delivery place')}}
                    @endif
                </b>
            </h5>
            <div class="card-text">
                <p>
                    @foreach($deliveryPlacesArray as $deliveryPlace)
                        @if(($order->order_status == "collected" && $deliveryPlace['delivered']) || ($order->order_status == "delivered" || $order->order_status == "completed"))
                            <i class="fa fa-check-circle text-success" title="{{__('Delivered')}}"></i>
                        @elseif(in_array($order->order_status,["waiting","pending","accepted","collected"]))
                            <i class="fa fa-clock text-warning" title="{{__('Waiting')}}"></i>
                        @else
                            <i class="fa fa-times-circle text-danger" title="{{__('Cancelled')}}"></i>
                        @endif
                        {{$deliveryPlace['formatted_address']." - ".$deliveryPlace['number']}}<br>
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</div>
@if($order->need_return_to_pickup_location)
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <b> <i class="fas fa-undo"></i>
                        {{__('Return to pickup location')}}
                    </b>
                </h5>
                <div class="card-text">
                    <p>
                        @if(in_array($order->order_status,['waiting','pending','accepted','collected','delivered']))
                            <i class="fa fa-clock text-warning" title="{{__('Waiting')}}"></i>
                        @elseif($order->order_status == "completed")
                            <i class="fa fa-check-circle text-success" title="{{__('Completed')}}"></i>
                        @else
                            <i class="fa fa-times-circle text-danger" title="{{__('Cancelled')}}"></i>
                        @endif
                        {{$pickupLocationData['formatted_address']." - ".$pickupLocationData['number']}}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

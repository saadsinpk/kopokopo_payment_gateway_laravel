<!-- User Id Field -->
<div class="form-group col-sm-4">
    {!! Form::label('user_id', __('User').':') !!}
    <p>{{__('Name').': '.$courier->user->name }}<br>
       {{__('Phone').': '.$courier->user->phone }}<br>
       {{__('Email').': '.$courier->user->email }}<br>
    </p>
</div>

<!-- Active Field -->
<div class="form-group col-sm-4">
    {!! Form::label('active', __('Active').':') !!}
    <p>{!!  getBoolColumn($courier->active)  !!}</p>
</div>

<!-- Last Location At Field -->
<div class="form-group col-sm-4">
    {!! Form::label('last_location_at', __('Last Location At').':') !!}
    <p>{!! (is_null($courier->last_location_at)?'-':getDateHumanFormat($courier->last_location_at)) !!}</p>
</div>
<div class="col-sm-6">
    <!-- Using App Pricing Field -->
    <div class="form-group">
        {!! Form::label('using_app_pricing', __('Using App Pricing').':') !!}
        <p>{!! getBoolColumn($courier->using_app_pricing) !!}</p>
    </div>
    @if(!$courier->using_app_pricing)
        <!-- Base Price Field -->
        <div class="form-group">
            {!! Form::label('base_price', __('Base Price').':') !!}
            <p>{{ $courier->base_price }}</p>
        </div>

        <!-- Base Distance Field -->
        <div class="form-group">
            {!! Form::label('base_distance', __('Base Distance').':') !!}
            <p>{{ $courier->base_distance }}</p>
        </div>

        <!-- Additional Distance Pricing Field -->
        <div class="form-group">
            {!! Form::label('additional_distance_pricing', __('Additional Distance Pricing').':') !!}
            <p>{{ $courier->additional_distance_pricing }}</p>
        </div>

        <!-- Return Distance Pricing Field -->
        <div class="form-group">
            {!! Form::label('return_distance_pricing', __('Return Distance Pricing').':') !!}
            <p>{{ $courier->return_distance_pricing }}</p>
        </div>

        <!-- Additional Stop Tax Field -->
        <div class="form-group">
            {!! Form::label('additional_stop_tax', __('Additional Stop Tax').':') !!}
            <p>{{ $courier->additional_stop_tax }}</p>
        </div>
    @endif

</div>
<div class="col-sm-6">
    <div class="row">
        <!-- Lat Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('actual_location', __('Last Location').':') !!}
            <div id="mapsHere" style="height: 300px">

            </div>
        </div>
    </div>
    <div class="row">
        <!-- Lat Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('lat', __('Latitude').':') !!}
            <p>{{ $courier->lat }}</p>
        </div>

        <!-- lng Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('lng', __('Longitude').':') !!}
            <p>{{ $courier->lng }}</p>
        </div>
    </div>
</div>
@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{setting('google_maps_key')}}&libraries=places&callback=initMap" async></script>
    <script type="text/javascript">
        function initMap() {
            var latlng = [];
            const myLatLng = { lat: {{$courier->lat}}, lng: {{$courier->lng}} };
            const map = new google.maps.Map(document.getElementById("mapsHere"), {
                zoom: 16,
                center: myLatLng,
            });

            latlng.push(new google.maps.LatLng({{$courier->lat}}, {{$courier->lng}}));
            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "{{__('Courier Location')}}",
            });



        }

        window.initMap = initMap;
    </script>
@endpush

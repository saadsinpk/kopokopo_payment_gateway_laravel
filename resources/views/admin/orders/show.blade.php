@extends('layouts.app')
@include('orders.chat_modal')
@section('title')
    {{ __('Order Details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Order Details') }}: {{ $order->id }}</h1>
            <div class="section-header-breadcrumb">
                <a id="openChat" href="#" data-order_id="{{ $order->id }}" data-toggle="modal" data-target="#chatModal"
                    class='btn btn-success form-btn float-right'>
                    {{ __('Chat') }}
                </a>
                &nbsp;&nbsp;
                <a href="{{ route('admin.orders.edit', $order->id) }}"
                    class="btn btn-warning form-btn float-right">{{ __('crud.edit') }}</a>
                &nbsp;&nbsp;
                <a href="{{ route('admin.orders.index') }}"
                    class="btn btn-primary form-btn float-right">{{ __('Back') }}</a>
            </div>
        </div>
        @include('flash::message')
        @include('stisla-templates::common.errors')
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('admin.orders.show_fields')
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initMap"
        async></script>
    <script type="text/javascript">
        $(document).ready(function() {
            loadAddresses();
        });

        function loadAddresses() {
            $.ajax({
                url: "{{ route('admin.orders.ajaxGetAddressesHtml') }}",
                type: "GET",
                dataType: "html",
                data: {
                    order_id: '{{ $order->id }}',
                },
                success: function(data) {
                    $('#adressesContainer').html(data);
                }
            });
        }


        function initMap() {
            var latlng = [];
            const myLatLng = {
                lat: {{ json_decode($order->pickup_location_data)->geometry->location->lat }},
                lng: {{ json_decode($order->pickup_location_data)->geometry->location->lng }}
            };
            const map = new google.maps.Map(document.getElementById("mapsHere"), {
                zoom: 10,
                center: myLatLng,

            });

            latlng.push(new google.maps.LatLng({{ json_decode($order->pickup_location_data)->geometry->location->lat }},
                {{ json_decode($order->pickup_location_data)->geometry->location->lng }}));

            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "Pickup Location",
            });

            @foreach (json_decode($order->delivery_locations_data) as $key => $deliveryPlace)
                new google.maps.Marker({
                    position: {
                        lat: {{ $deliveryPlace->geometry->location->lat }},
                        lng: {{ $deliveryPlace->geometry->location->lng }}
                    },
                    map,
                    title: "Delivery Place {{ $key }}",
                });
                latlng.push(new google.maps.LatLng({{ $deliveryPlace->geometry->location->lat }},
                    {{ $deliveryPlace->geometry->location->lng }}));
            @endforeach

            var latlngbounds = new google.maps.LatLngBounds();
            for (var i = 0; i < latlng.length; i++) {
                latlngbounds.extend(latlng[i]);
            }
            map.fitBounds(latlngbounds);

        }

        window.initMap = initMap;
    </script>
@endpush

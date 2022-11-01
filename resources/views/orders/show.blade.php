@extends('layouts.public')
@include('orders.chat_modal')
@section('title')
    {{ __('Order #:id - :name', ['id' => $order->id, 'name' => $order->courier->user->name]) }}
@endsection
@section('content')
    <style type="text/css">
        .orderDetailsRow {
            border-bottom: 1px solid #eee;
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>
    <div class="container">
        <div class="container newOrderContainer">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h5 class="card-title text-center" style="color: #00a65a">
                        {{ __('Order #:id - :name', ['id' => $order->id, 'name' => $order->courier->user->name]) }}
                    </h5>
                </div>
            </div>
            @include('flash::message')
            @if (!$order->offline_payment_method_id)
                @if ($order->payment_status == 'pending')
                    @include('orders.include.' . $order->payment_gateway . '_payment')
                @endif
            @endif
            <div class="row mb-5 mx-1">
                <div class="col-md-6 text-center p-2" id="statusFeedBack">
                    <i class="fas fa-spinner fa-spin" style="font-size: 24px"></i>
                </div>
                <div class="col-md-6 text-center">
                    <div id="mapsHere" style="min-height: 400px"></div>
                    <label class="text-muted" style="font-size: 9px">
                        {{ __('The courier position is updated automatically when the order is in progress') }}
                    </label>
                </div>
                @if (in_array($order->order_status, ['accepted', 'collected', 'delivered']))
                    <div class="col-md-6 ">
                        <a id="openChat" href="#" data-order_id="{{ $order->id }}" data-toggle="modal"
                            data-target="#chatModal" class='btn btn-sm btn-outline-success btn-block'><i
                                class="fa fa-comments"></i>
                            {{ __('Order Chat') }}
                        </a>
                    </div>
                @endif
            </div>
            <div class="row" id="adressesContainer">

            </div>
            <div class="row" style="padding-top: 25px">
                <div class="col-12 text-center">
                    <h5>{{ __('Order Details') }}</h5>
                </div>
            </div>
            @if ($order->offline_payment_method_id == 0)
                <div class="row orderDetailsRow">
                    <div class="col-sm-4 text-sm-right">
                        <h6>{{ __('Payment') }}</h6>
                    </div>
                    <div class="col-sm-8 text-left">
                        @if ($order->payment_status == 'paid')
                            <h6 class="text-success">
                                <i class="fas fa-check" style="font-size: 24px"></i>
                                {{ __('Payment Approved') }}
                            </h6>
                        @elseif($order->order_status == 'cancelled')
                            <h6 class="text-danger">
                                <i class="fas fa-times-circle" style="font-size: 24px"></i>
                                {{ __('Order Cancelled') }}
                            </h6>
                        @else
                            <h6 class="text-warning">
                                <i class="fas fa-exclamation-circle" style="font-size: 24px"></i>
                                {{ __('Payment :status', ['status' => ucwords($order->payment_status)]) }}
                            </h6>
                        @endif
                    </div>
                </div>
            @else
                <div class="row orderDetailsRow">
                    <div class="col-sm-4 text-sm-right">
                        <h6>{{ __('Payment Method') }}</h6>
                    </div>
                    <div class="col-sm-8 text-left">
                        {{ $order->OfflinePaymentMethod->name }}
                    </div>
                </div>
            @endif
            <div class="row orderDetailsRow">
                <div class="col-sm-4 text-sm-right">
                    <h6>{{ __('Order Date') }}</h6>
                </div>
                <div class="col-sm-8 text-left">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="row orderDetailsRow">
                <div class="col-sm-4 text-sm-right">
                    <h6>{{ __('Distance') }}</h6>
                </div>
                <div class="col-sm-8 text-left">
                    {{ number_format($order->distance, 3, '.', '') }} {{ setting('distance_unit') }}
                </div>
            </div>
            <div class="row orderDetailsRow">
                <div class="col-sm-4 text-sm-right">
                    <h6>{{ __('Total') }}</h6>
                </div>
                <div class="col-sm-8 text-left">
                    {!! getPrice($order->total_value) !!}
                </div>
            </div>
            <div class="row orderDetailsRow">
                <div class="col-sm-4 text-sm-right">
                    <h6>{{ __('Return required') }}</h6>
                </div>
                <div class="col-sm-8 text-left">
                    @if ($order->need_return_to_pickup_location)
                        <h6 class="text-success">
                            <i class="fas fa-check" style="font-size: 18px"></i>
                            {{ __('Yes') }}
                        </h6>
                    @else
                        <h6 class="text-danger">
                            <i class="fas fa-times-circle" style="font-size: 18px"></i>
                            {{ __('No') }}
                        </h6>
                    @endif
                </div>
            </div>
            <div class="row orderDetailsRow">
                <div class="col-sm-4 text-sm-right">
                    <h6>{{ __('Observation') }}</h6>
                </div>
                <div class="col-sm-8 text-left">
                    {{ $order->customer_observation ?? '-' }}
                </div>
            </div>


            <div class="card" style="width: 100%;margin-top: 20px;border-radius: 10px">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <img src="{{ $order->courier->user->media->first()->url ?? '/img/avatardefault.png' }}"
                                style="width: 80px;height:80px;border-radius: 100%">
                        </div>
                        <div class="col-md-10 text-center text-md-left ">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">{{ $order->courier->user->name }}
                                    </h5>
                                </div>
                                <div class="col-md-6 text-center">
                                    <a href="{{ url($order->courier->slug) }}"
                                        class='btn btn-sm btn-outline-success btn-block'
                                        target='_blank'>{{ __('New Order') }}</a>
                                    <br>
                                    <a href="#" class="text-gray-dark" data-toggle="modal" data-target="#modalPedido">
                                        {{ __('Courier Contact') }}
                                    </a>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right mt-4">
                    @if ($order->order_status == 'pending')
                        <button class="btn btn-sm btn-outline-danger" id="btnCancelOrder">{{ __('Cancel Order') }}</button>
                    @endif

                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <!-- Modal -->
    <div class="modal fade" id="modalPedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Courier Contact') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        <b>{{ __('Name') }}:</b> {{ $order->courier->user->name }}<br>
                        <b>{{ __('Email') }}:</b> <a
                            href="mailto:{{ $order->courier->user->email }}">{{ $order->courier->user->email }}</a><br>
                        <b>{{ __('Phone') }}:</b> {{ $order->courier->user->phone ?? '-' }}<br>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5.0.6/bootstrap-4.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css"
        integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js"
        integrity="sha512-L3moecKIMM1UtlzYZdiGlge2+bugLObEFLOFscaltlJ82y0my6mTUugiz6fQiSc5MaS7Ce0idFJzabEAl43XHg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js"></script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initMap"
        async></script>
    <script type="text/javascript">
        var map;
        var latlng = [];
        var latlngbounds;

        function initMap() {
            const myLatLng = {
                lat: {{ json_decode($order->pickup_location_data)->geometry->location->lat }},
                lng: {{ json_decode($order->pickup_location_data)->geometry->location->lng }}
            };
            map = new google.maps.Map(document.getElementById("mapsHere"), {
                zoom: 10,
                center: myLatLng,

            });

            latlng.push(new google.maps.LatLng({{ json_decode($order->pickup_location_data)->geometry->location->lat }},
                {{ json_decode($order->pickup_location_data)->geometry->location->lng }}));

            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "{{ __('Pickup Location') }}",
            });

            @foreach (json_decode($order->delivery_locations_data) as $key => $deliveryPlace)
                new google.maps.Marker({
                    position: {
                        lat: {{ $deliveryPlace->geometry->location->lat }},
                        lng: {{ $deliveryPlace->geometry->location->lng }}
                    },
                    map,
                    title: "{{ __('Delivery Place') }} {{ $key }}",
                });
                latlng.push(new google.maps.LatLng({{ $deliveryPlace->geometry->location->lat }},
                    {{ $deliveryPlace->geometry->location->lng }}));
            @endforeach

            latlngbounds = new google.maps.LatLngBounds();
            for (var i = 0; i < latlng.length; i++) {
                latlngbounds.extend(latlng[i]);
            }
            map.fitBounds(latlngbounds);

        }

        window.initMap = initMap;


        var courierMarker;
        $(document).ready(function() {
            loadAddresses();
            loadOrderStatus();
            $('#btnCancelOrder').click(function() {
                cancelOrder();
            });
        });
        if (!Array.prototype.last) {
            Array.prototype.last = function() {
                return this[this.length - 1];
            };
        };

        function cancelOrder() {
            if (confirm("{{ __('Are you sure you want to cancel this order?') }}")) {
                $('#btnCancelOrder').html('<i class="fas fa-spinner fa-spin"></i>');
                $.ajax({
                    url: "{{ url('api/orders/cancel') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        api_token: "{{ Auth::user()->api_token }}",
                        order_id: '{{ $order->id }}',
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: '{{ __('Order cancelled') }}',
                                text: '{{ __('Order cancelled successfully') }}',
                                type: 'success',
                                icon: 'success',
                                confirmButtonText: '{{ __('Ok') }}'
                            }).then((result) => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: '{{ __('Oops') }}',
                                text: data.message,
                                type: 'error',
                                icon: 'error',
                                confirmButtonText: '{{ __('Ok') }}'
                            });
                            $('#btnCancelOrder').html('{{ __('Cancel order') }}');
                        }
                    },
                });
            }
        }

        function loadAddresses() {
            $.ajax({
                url: "{{ route('orders.ajaxGetAddressesHtml') }}",
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

        function loadOrderStatus() {
            $.ajax({
                url: "{{ url('api/orders/getStatus') }}",
                type: "POST",
                data: {
                    api_token: "{{ Auth::user()->api_token }}",
                    order_id: '{{ $order->id }}',
                },
                success: function(data) {
                    let constructedHtml = '';
                    if (data.image) {
                        constructedHtml += '<img src="' + data.image + '" style="' + ((data.image.split(".")
                            .last() != "gif") ? 'width:250px' : '') + '">';
                    }
                    if (data.status_title) {
                        constructedHtml += '<br><h2 style="color:#666666">' + data.status_title + '</h2>';
                    }
                    if (data.status_desc) {
                        constructedHtml += '<p style="color:#666666">' + data.status_desc + '</p>';
                    }
                    constructedHtml += '<div style="margin-top: -10px;font-size: 11px">' + data.status_date +
                        '</div>';

                    $('#statusFeedBack').html(constructedHtml);

                    if (data.show_courier_position && !courierMarker) {
                        showCourierPosition();
                    }

                    if (data.need_recheck > 0) {
                        setTimeout(function() {
                            loadOrderStatus();
                            loadAddresses();
                        }, data.need_recheck);
                    }
                }
            });
        }

        function showCourierPosition() {
            $.ajax({
                url: "{{ url('api/orders/getCourierPosition') }}",
                type: "POST",
                data: {
                    api_token: "{{ Auth::user()->api_token }}",
                    order_id: '{{ $order->id }}',
                },
                success: function(data) {
                    if (data.success) {
                        //create or update courier marker
                        if (!courierMarker) {
                            courierMarker = new google.maps.Marker({
                                position: {
                                    lat: data.lat,
                                    lng: data.lng
                                },
                                map,
                                icon: '{{ asset('img/couriermarker.png') }}',
                                title: "{{ __('Courier Position') }}",
                            });
                        } else {
                            courierMarker.setPosition({
                                lat: data.lat,
                                lng: data.lng
                            });
                        }

                        latlngbounds.extend(courierMarker.getPosition());
                        map.fitBounds(latlngbounds);
                        setTimeout(function() {
                            showCourierPosition();
                        }, 5000);
                    } else {
                        //remove the courier position
                        removeCourierPosition();
                    }
                }
            });
        }



        function removeCourierPosition() {
            if (courierMarker) {
                courierMarker.setMap(null);
            }
        }
    </script>
@endpush

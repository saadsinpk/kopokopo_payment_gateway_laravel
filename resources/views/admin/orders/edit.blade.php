@extends('layouts.app')
@section('title')
    {{ __('Edit Order') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">{{ __('Edit Order') }}</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
            </div>
        </div>
        <div class="content">
            @include('flash::message')
            @include('stisla-templates::common.errors')
            <div class="section-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body ">
                                {!! Form::model($order, ['route' => ['admin.orders.update', $order->id], 'method' => 'patch']) !!}
                                <div class="row">
                                    @include('admin.orders.fields')
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
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
        async function getTheNumber(place, theInput) {
            let extractedNumber = '';
            if (place.name.split(', ').length > 1) {
                extractedNumber = place.name.split(', ').pop();
            }
            //input the number manually
            const {
                value: number
            } = await Swal.fire({
                title: "{{ __('Enter the number and complement') }}",
                text: theInput.val(),
                input: 'text',
                allowOutsideClick: false,
                inputValue: extractedNumber,
                inputPlaceholder: 'Ex. 123',
                inputAttributes: {
                    'aria-label': "{{ __('Enter the location number and complement') }}"
                },
                showCancelButton: true,
                cancelButtonText: "{{ __('Cancel') }}",
                inputValidator: (value) => {
                    if (!value) {
                        return "{{ __('You need to enter a value!') }}"
                    }
                }
            });

            return (number !== null && number != "") ? number : getTheNumber(place, theInput);

        }

        function initMap() {
            const input = document.getElementById("inputCollectPlace");
            const options = {
                fields: ["formatted_address", "geometry", "name"],
                strictBounds: false,
            };

            const autocomplete = new google.maps.places.Autocomplete(input, options);

            autocomplete.addListener("place_changed", async () => {
                const place = autocomplete.getPlace();
                place["number"] = await getTheNumber(place, $('#inputCollectPlace'));
                $('input[name="collect_address_data"]').val(JSON.stringify(place));

                calculateOrder();


            });

            const input2 = document.getElementById("inputDeliveryPlace");

            const autocomplete2 = new google.maps.places.Autocomplete(input2, options);

            autocomplete2.addListener("place_changed", async () => {
                const place2 = autocomplete2.getPlace();
                place2["number"] = await getTheNumber(place2, $('#inputDeliveryPlace'));
                $('input[name="delivery_address_tmp"]').val(JSON.stringify(place2));
                calculateOrder();
            });

        }
        $(document).ready(function() {
            var customer_limit = 20;
            var courier_limit = 20;
            $('#customer_id').select2({
                ajax: {
                    url: '{{ route('admin.customersJson') }}',
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            'limit': customer_limit,
                            'offset': ((params.page || 1) - 1) * customer_limit
                        };
                        if (params.term != undefined) {
                            query.search = 'name:' + params.term;
                            query.searchFields = 'name:like';
                        }
                        return query;
                    },
                    processResults: function(dataResponse, params) {
                        params.page = params.page || 1;
                        var data = $.map(dataResponse['data'], function(obj) {
                            return {
                                'id': obj.id,
                                'text': obj.name
                            };
                        });
                        return {
                            results: data,
                            pagination: {
                                more: data.length == customer_limit
                            }
                        };
                    }
                }
            });
            $('#courier_id').select2({
                ajax: {
                    url: '{{ route('admin.couriersJson') }}',
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            'limit': courier_limit,
                            'offset': ((params.page || 1) - 1) * courier_limit
                        };
                        if (params.term != undefined) {
                            query.search = 'user.name:' + params.term;
                            query.searchFields = 'user.name:like';
                        }
                        return query;
                    },
                    processResults: function(dataResponse, params) {
                        params.page = params.page || 1;
                        var data = $.map(dataResponse['data'], function(obj) {
                            return {
                                'id': obj.id,
                                'text': obj.name
                            };
                        });
                        return {
                            results: data,
                            pagination: {
                                more: data.length == courier_limit
                            }
                        };
                    }
                }
            });
            $('#inputCollectPlace').on('keyup', function() {
                $('input[name="collect_address_data"]').val('');
            });

            $('body').on('mousedown touchstart,click', '.btnAddAddress', function() {
                //add a new address
                let addressData = $('input[name="delivery_address_tmp"]').val();
                let addressText = $('#inputDeliveryPlace').val();

                if (addressData != "") {
                    addressDataJson = JSON.parse(addressData);
                    if (addressDataJson.number != undefined && addressDataJson.number != "") {
                        let html = `<div class="row mb-1">
                                        <div class="col-lg-11 col-10 mr-0 pr-0">
                                            <input type="hidden" name="delivery_address_data[]" value='` +
                            addressData +
                            `'>
                                            <input type="text" class="form-control" disabled="true" name="delivery_place[]" value="` +
                            addressText + `" style="height:30px;border-bottom-right-radius: 0px;border-top-right-radius: 0px;">
                                            <div class="invalid-feedback" data-name="delivery_place">
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-2 ml-0 pl-0">
                                            <button type="button" class="btn btn-outline-danger btn-block btnRemoveAddress" style="height:30px;line-height:20px;border-bottom-left-radius: 0px;border-top-left-radius: 0px;">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>`;
                        $('#addressesList').append(html);
                        $('input[name="delivery_address_tmp"]').val("");
                        $('#inputDeliveryPlace').val("");
                        calculateOrder();
                    } else {
                        return Swal.fire({
                            type: 'error',
                            icon: 'error',
                            title: "{{ __('You need to enter and select the delivery address to continue') }}",
                        });
                    }
                } else {
                    return Swal.fire({
                        type: 'error',
                        icon: 'error',
                        title: "{{ __('You need to enter and select the delivery address to continue') }}",
                    });
                }
            });
        });
    </script>
@endpush

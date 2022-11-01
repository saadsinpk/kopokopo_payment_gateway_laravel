@extends('layouts.public')
@section('title')
    {!! setting('header_text') !!}
@endsection
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                @if(setting('header_text'))
                <h2 class="headerText">
                    {!! setting('header_text') !!}
                </h2>
                @endif
                @if(setting('subheader_text'))
                    <h4 class="subheader">
                        {!! setting('subheader_text') !!}
                    </h4>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12" style="text-align: center">
                <h6 class="what-we-can-do-today">{{ __('See what we can do for you today') }}</h6>
                <ul class="orderingTypes" id="letsanimate">
                    @foreach($categories as $category)
                        <li class="orderingType">

                            <img src="{{ $category->getFirstMediaUrl('default') }}" alt="{{ $category->name }}">
                            <h6>{{ $category->name }}</h6>

                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
        <div class="container newOrderContainer">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h5 class="card-title green-text text-center">
                        {{ __('Request Delivery Now') }}
                    </h5>
                </div>
            </div>
            <form method="POST" id="formOrderDelivery">
                <input type="hidden" name="api_token" value="{{ Auth::user()->api_token??'' }}">
                <div class="row pickupPlaceInput">
                    <div class="col-md-12">
                        <label for="inputCollectPlace">{{ __('Pick-up Location') }}</label>
                    </div>
                    <div class="col-md-12" style="vertical-align: center">
                        <input type="hidden" name="collect_address_data" value="{{$lastCollectAddressData??''}}">
                        <input type="text" class="form-control" id="inputCollectPlace" tabindex="4" name="collect_place" placeholder="{{__('Enter and select the street, neighborhood and city of the pickup location')}}" tabindex="1" value="{{ $lastCollectAddress??'' }}" required>
                        <input type="hidden" name="save_data" value="0">
                        <label style="vertical-align: middle;margin-top: 10px;color: #333333">
                            <input type="checkbox" name="save_data" value="1" style="width:20px;height:20px" checked="true" tabindex="0">
                            {{__('Save pickup location for next order')}}
                        </label>
                        <div class="invalid-feedback" data-name="collect_place">
                        </div>
                    </div>
                </div>
                <div class="form-group mt-2">

                </div>
                <div class="form-group">
                    <label>{{__('Delivery Location(s)')}}</label>
                    <div id="addressesList">

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
                <div class="form-group">
                    <input type="hidden" name="return_required" value="0">
                    <label style="vertical-align: middle">
                        <input type="checkbox" name="return_required" tabindex="5" value="1" style="width:20px;height:20px">
                        {{__('Return to the pickup location is required')}}
                    </label>
                    <div class="invalid-feedback" data-name="collect_place">
                    </div>
                </div>
                <div class="form-group" id="userFeedback">
                    <center>
                        <br>
                        <img src="{{asset('img/urban-motorcycle-deliery.png')}}" style="width: 250px;height: auto">
                        <br><br>
                        <h4 style="color: #666666">
                            {{__('Start by entering the pickup location')}} 👆
                        </h4>
                    </center>
                </div>

                <div class="form-group text-center" id="divResume" style="display: none">
                    <h4>{{__('Summary')}}</h4>
                    <table class="table table-striped">
                        <tr>
                            <th>{{__('Distance')}}</th>
                            <td id="totalDistance">-</td>
                        </tr>
                        <tr>
                            <th>{{__('Total')}}</th>
                            <td id="totalPrice">-</td>
                        </tr>
                    </table>
                </div>



                <div class="form-group" id="divSubmit" style="display: block">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{__('Observation for the Courier')}}</label>
                                <textarea class="form-control" id="inputObservation" tabindex="6" name="observation" placeholder="{{__('If needed, input an observation for the courier')}}" value="" style="height: 100px"></textarea>
                                <div class="invalid-feedback" data-name="observation">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center py-3">
                            <input type="hidden" name="payment_method" value="">
                            <input type="hidden" name="payment_method_type" value="">
                            <h4 style="text-align: center;font-size: 1.2rem">{{__('Select the payment method')}}</h4>
                        </div>
                    </div>
                    @include('orders.include.payment_methods')
                </div>
            </form>
        </div>
        <div class="card card-success">
            <div class="card-body">
                <h4>{{__('How it works:')}}</h4>
                <div class="stepper d-flex flex-column mt-5 ml-2">
                    <div class="d-flex mb-1">
                        <div class="d-flex flex-column pr-4 align-items-center">
                            <div class="rounded-circle py-2 px-3 bg-primary text-white mb-1">1</div>
                            <div class="line h-100"></div>
                        </div>
                        <div>
                            <h5 class="text-dark">{{__('Find the near couriers of you')}}</h5>
                            <p class="lead text-muted pb-3">
                                {{__('We find the delivery person closest to your pick-up location and, if you want, we allow you to choose from the delivery options! You can choose the delivery person you want or order with one you already know.')}}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex mb-1">
                        <div class="d-flex flex-column pr-4 align-items-center">
                            <div class="rounded-circle py-2 px-3 bg-primary text-white mb-1">2</div>
                            <div class="line h-100"></div>
                        </div>
                        <div>
                            <h5 class="text-dark">{{__('Follow your order!')}}</h5>
                            <p class="lead text-muted pb-3">{{__('Your order is sent directly to the chosen courier and you can track the status of the delivery directly from the order panel!')}}</p>
                        </div>
                    </div>
                    <div class="d-flex mb-1">
                        <div class="d-flex flex-column pr-4 align-items-center">
                            <div class="rounded-circle py-2 px-3 bg-primary text-white mb-1">3</div>
                            <div class="line h-100 d-none"></div>
                        </div>
                        <div>
                            <h5 class="text-dark">{{__('Flexible Options')}}</h5>
                            <p class="lead text-muted pb-3">
                                {{__('It is possible to ask the delivery person to return or not to the pick-up location, compare the prices in real time and have a history to choose the best delivery person for your order!')}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')

            @if(auth()->check())
                <div class="modal fade" id="modalDeliveryMen" tabindex="-1" aria-labelledby="modalDeliveryMen" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalDeliveryMen">{{__('Couriers near you')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modalListDeliveryMenAvailable">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalConfirmOrder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{__('Confirm your order')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modalConfirmOrderContent">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>{{__('Pick-up place')}}</th>
                                                <td id="pickupPlaceConfirm"></td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Delivery place')}}</th>
                                                <td id="deliveryPlaceConfirm"></td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Required pick-up place return')}}</th>
                                                <td id="pickupPlaceReturnConfirm"></td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Courier')}}</th>
                                                <td id="deliveryMenConfirm"></td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Payment Method')}}</th>
                                                <td id="paymentMethod"></td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Distance')}}</th>
                                                <td id="distanceConfirm"></td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Total Price')}}</th>
                                                <td id="totalValueConfirm"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" id="btnSendOrder" class="btn btn-success btn-lg btn-block" tabindex="7" disabled>
                                            {{__('Send Order')}}
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://maps.googleapis.com/maps/api/js?key={{setting('google_maps_key')}}&libraries=places&callback=initMap" async></script>
                <script type="text/javascript">
                    var alreadyContinued = false;
                    var selectedDeliveryMen = {};
                    var couriersList = [];
                    async function getTheNumber(place,theInput) {
                        let extractedNumber = '';
                        if(place.name.split(', ').length > 1){
                            extractedNumber = place.name.split(', ').pop();
                        }
                        //input the number manually
                        const {value: number} = await Swal.fire({
                            title: "{{__('Enter the number and complement')}}",
                            text: theInput.val(),
                            input: 'text',
                            allowOutsideClick: false,
                            inputValue: extractedNumber,
                            inputPlaceholder: 'Ex. 123',
                            inputAttributes: {
                                'aria-label': "{{__('Enter the location number and complement')}}"
                            },
                            showCancelButton: true,
                            cancelButtonText: "{{__('Cancel')}}",
                            inputValidator: (value) => {
                                if (!value) {
                                    return "{{__('You need to enter a value!')}}"
                                }
                            }
                        });

                        return (number !== null && number != "")?number:getTheNumber(place,theInput);

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
                            place["number"] = await getTheNumber(place,$('#inputCollectPlace'));
                            $('input[name="collect_address_data"]').val(JSON.stringify(place));
                            seachDeliveryMenForOrder().then(() => {
                                calculateOrder();
                            });

                        });

                        const input2 = document.getElementById("inputDeliveryPlace");

                        const autocomplete2 = new google.maps.places.Autocomplete(input2, options);

                        autocomplete2.addListener("place_changed", async () => {
                            const place2 = autocomplete2.getPlace();
                            place2["number"] = await getTheNumber(place2,$('#inputDeliveryPlace'));
                            $('input[name="delivery_address_tmp"]').val(JSON.stringify(place2));
                            calculateOrder();
                        });

                    }
                    let needDeleteIfcollectInputed = false;
                    $(document).ready(function(){

                        @if(isset($lastCollectAddressData) && strlen($lastCollectAddressData) > 2)
                            needDeleteIfcollectInputed = true;
                        @endif

                        $('#inputCollectPlace').on('keyup',function() {
                            if (needDeleteIfcollectInputed) {
                                $('input[name="collect_address_data"]').val('');
                                needDeleteIfcollectInputed = false;
                            }
                        });

                        $('body').on('mousedown touchstart,click','.btnAddAddress',function(){
                            //add a new address
                            let addressData = $('input[name="delivery_address_tmp"]').val();
                            let addressText = $('#inputDeliveryPlace').val();

                            if(addressData != ""){
                                addressDataJson = JSON.parse(addressData);
                                if(addressDataJson.number != undefined && addressDataJson.number != ""){
                                    let html = `<div class="row mb-1">
                                        <div class="col-lg-11 col-10 mr-0 pr-0">
                                            <input type="hidden" name="delivery_address_data[]" value='`+addressData+`'>
                                            <input type="text" class="form-control" disabled="true" name="delivery_place[]" value="`+addressText+`" style="height:30px;border-bottom-right-radius: 0px;border-top-right-radius: 0px;">
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
                                }else{
                                    return Swal.fire({
                                        type:'error',
                                        icon:'error',
                                        title: "{{__('You need to enter and select the delivery address to continue')}}",
                                    });
                                }
                            }else{
                                return Swal.fire({
                                    type:'error',
                                    icon: 'error',
                                    title: "{{__('You need to enter and select the delivery address to continue')}}",
                                });
                            }
                        });

                        $('body').on('mousedown touchstart,click','.btnRemoveAddress',function(){
                            $(this).closest('.row').remove();
                            calculateOrder();
                        });

                        $('body').on('change','input[name="return_required"]',function(){
                            calculateOrder();
                        });

                        //[TODO] Adapt
                        $('#btnSendOrder').click(function(){
                            $('#btnSendOrder').attr('disabled',true);
                            let beforeValue = $('#btnSendOrder').html();
                            $('#btnSendOrder').html('<i class="fas fa-spinner fa-spin"></i>');

                            $.ajax({
                                url: '{{url('/api/orders')}}',
                                type: 'POST',
                                dataType: 'json',
                                data: $('#formOrderDelivery').serialize(),
                                success:function(data){
                                    if(data.success) {
                                        window.location.href = '{{url('/orders')}}/'+data.id;
                                    }else{
                                        $('#btnSendOrder').removeAttr('disabled');
                                        $('#btnSendOrder').html(beforeValue);
                                        return Swal.fire({
                                            type:'error',
                                            icon:'error',
                                            title: data.message??'{{__('There was an error calculating delivery')}}',
                                        });
                                    }
                                },
                                error:function(data){
                                    data = data.responseJSON;
                                    $('#btnSendOrder').html(beforeValue);
                                    $('#btnSendOrder').removeAttr('disabled');
                                    return Swal.fire({
                                        type:'error',
                                        icon:'error',
                                        title: data.message??'{{__('There was an error calculating delivery')}}',
                                    });
                                },
                            });

                        });

                        @if(!empty($lastCollectAddress) && !empty($lastCollectAddressData))
                        seachDeliveryMenForOrder();
                        @endif
                    });


                    function returnHtmlCardOrder(selectedDeliveryMen,isSelected = false){
                        return `
                                <div class="card `+((!isSelected)?'btnSwitchDeliveryMen':'')+`" data-id="`+selectedDeliveryMen.id+`" style="width: 100%; `+((!isSelected)?'cursor:pointer':'')+`">
                                    <div class="card-body">`+(isSelected?`
                                        <input type="hidden" name="slug" value="`+selectedDeliveryMen.slug+`">
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <h5 class="card-title text-center" style="color: #00a65a">
                                                    {{__('Nearby courier found')}}
                                                </h5>
                                            </div>
                                        </div>`:'')+`
                                        <div class="row">
                                            <div class="col-sm-3 text-center">
                                                <img src="`+((selectedDeliveryMen.avatar.length > 0)?selectedDeliveryMen.avatar:'{{asset('/img/avatardefault.png')}}')+`" style="width: 100px;height:100px;border-radius: 100%;object-fit: contain;">
                                            </div>
                                            <div class="col-sm-9 text-center text-md-left">
                                                <h5 class="card-title">`+selectedDeliveryMen.name+`</h5>
                                                <h6 class="card-subtitle mb-2 text-muted">
                                                    <span class="badge badge-success">`+selectedDeliveryMen.orders_count+`</span> `+selectedDeliveryMen.distance+` {{__("from the pick-up location")}}
                                                </h6>
                                                <div class="card-text">
                                                `+(isSelected?`<small>
                                                        <i class="fas fa-check" style="color: #00a65a"></i> {{__('Deliveryman details are displayed on the order panel.')}}
                                                    </small>`:'')+`
                                                </div>
                                            </div>
                                        </div>
                                        `+(isSelected?`
                                        <div class="row">
                                            <div class="col-sm-12 text-right pt-2">
                                                <a href="#" class="text-muted btnSwitchDeliveryMenOrder" style="font-size: 14px">{{__('Change courier')}}</a>
                                            </div>
                                        </div>`:'')+`
                                    </div>
                                </div>
                                `;
                    }

                    async function seachDeliveryMenForOrder() {
                        $('#divResume').hide();
                        $('#divSubmit').hide();
                        $('#userFeedback').html('<center><img src="{{asset('img/pvirtual.gif')}}" alt="{{__('Loading')}}..."><br><h4 style="color:#777777">{{__('Searching for a courier near the pickup location')}}</h2></center>');
                        await $.ajax({
                            url: '{{url('/api/driver/findNearBy')}}',
                            type: 'POST',
                            dataType: 'json',
                            data: $('#formOrderDelivery').serialize(),
                            success:function(data){
                                if(data.success){
                                    if(data.data.length > 0){
                                        //encontrei o entregador
                                        couriersList = data.data;
                                        selectedDeliveryMen = couriersList[0];
                                        $('#userFeedback').html(returnHtmlCardOrder(selectedDeliveryMen,true));
                                        $('#divResume').show();
                                    }else{
                                        $('#userFeedback').html('<center><img src="{{asset('img/gps-disconnected.png')}}"><br><h6 style="color:#ff0000">{{__('Sorry, there are no couriers nearby to serve you at this address at the moment')}} 🙁</h6></center>');
                                    }
                                }else{
                                    $('#userFeedback').html('<center><img src="{{asset('img/gps-disconnected.png')}}"><br><h6 style="color:#ff0000">'+data.message+'</h6></center>');
                                }
                            },
                            error:function(data){
                                data = data.responseJSON;
                                $('#userFeedback').html('<center><img src="{{asset('img/gps-disconnected.png')}}"><br><h6 style="color:#ff0000">'+data.message+'</h6></center>');
                            }
                        });
                    }

                    $('body').on('mousedown touchstart,click','.btnSwitchDeliveryMen',function(){
                        let newId = $(this).attr('data-id');
                        let lastId = selectedDeliveryMen.id;
                        $.each(couriersList,function(i,v){
                            if(v.id == newId){
                                selectedDeliveryMen = v;
                                return;
                            }
                        });
                        $('#userFeedback').html(returnHtmlCardOrder(selectedDeliveryMen,true));

                        $('#modalDeliveryMen').modal('hide');
                        if(lastId != newId){
                            calculateOrder();
                        }

                    });

                    $('body').on('mousedown touchstart,click','.btnSwitchDeliveryMenOrder',function(){
                        htmlModal = '';
                        $.each(couriersList,function(i,v){
                            htmlModal += returnHtmlCardOrder(v,false)+"<hr>";
                        });
                        $('#modalListDeliveryMenAvailable').html(htmlModal);
                        $('#modalDeliveryMen').modal('show');
                    });

                    $('body').on('mousedown touchstart,click','.selectedPaymentMethod',function(){
                        let selectedPaymentMethod = $(this).attr('data-id');
                        let paymentMethodType = $(this).attr('data-type');
                        let paymentMethodText = $(this).text();
                        $('input[name="payment_method"]').val(selectedPaymentMethod);
                        $('input[name="payment_method_type"]').val(paymentMethodType);
                        $('#paymentMethod').html(paymentMethodText);

                        let pickupPlace = JSON.parse($('input[name="collect_address_data"]').val());
                        pickupPlace = pickupPlace.formatted_address+" - "+pickupPlace.number;
                        $('#pickupPlaceConfirm').html(pickupPlace);

                        let deliveryPlaces = [];

                        //delivery_address_data[]
                        let deliveriesPlacesInputs = $('input[name="delivery_address_data[]"');

                        $.each(deliveriesPlacesInputs,function (i,v){
                            let value = v.value;
                            if(value.length > 2) {
                                value = JSON.parse(value);
                                deliveryPlaces.push(value.formatted_address + ", N. " + value.number);
                            }
                        });

                        let deliveryPlaceTmp = $('input[name="delivery_address_tmp"]').val();
                        if(deliveryPlaceTmp.length > 2){
                            deliveryPlaceTmp = JSON.parse(deliveryPlaceTmp);
                            deliveryPlaces.push(deliveryPlaceTmp.formatted_address+", N. "+deliveryPlaceTmp.number);
                        }

                        if(deliveryPlaces.length > 1){
                            $('#deliveryPlaceLabel').html("{{__('Delivery places')}}");
                        }
                        $('#deliveryPlaceConfirm').html(deliveryPlaces.join("<br>"));

                        let returnRequired = $('input[type="checkbox"][name="return_required"]').is(":checked");
                        if(returnRequired){
                            $('#pickupPlaceReturnConfirm').html('{{__('yes')}}');
                        }else{
                            $('#pickupPlaceReturnConfirm').html('{{__('no')}}');
                        }

                        $('#deliveryMenConfirm').html(selectedDeliveryMen.name);


                        $('#distanceConfirm').html($('#totalDistance').html());
                        $('#totalValueConfirm').html($('#totalPrice').html());

                        $('#modalConfirmOrder').modal('show');
                    });

                    function calculateOrder(){
                        $('#divSubmit').hide();
                        $('#btnSendOrder').attr('disabled', 'disabled');
                        let beforeValue = "{{__('Place an Order')}}";
                        $('#btnSendOrder').html('<i class="fas fa-spinner fa-spin"></i>');
                        $('#totalDistance').html('<i class="fas fa-spinner fa-spin"></i>');
                        $('#totalPrice').html('<i class="fas fa-spinner fa-spin"></i>');

                        $.ajax({
                            url: '{{url('/api/orders/simulate')}}',
                            type: 'POST',
                            dataType: 'json',
                            data: $('#formOrderDelivery').serialize(),
                            success: function (data) {
                                $('#btnSendOrder').html(beforeValue);
                                $('#totalDistance').html(data.distance);
                                $('#totalPrice').html(data.price);
                                if (data.success) {
                                    if (data.enabled) {
                                        alreadyContinued = true;
                                        $('#btnSendOrder').removeAttr('disabled');
                                        $('#divSubmit').show();
                                    }else{
                                        alreadyContinued = false;
                                        $('#divSubmit').hide();
                                    }
                                } else {
                                    alreadyContinued = false;
                                    $('#divSubmit').hide();
                                    return Swal.fire({
                                        type: 'error',
                                        icon: 'error',
                                        title: data.message ?? '{{__('There was an error calculating delivery')}}',
                                    });
                                }
                            },
                            error: function (data) {
                                alreadyContinued = false;
                                $('#divSubmit').hide();
                                $('#btnSendOrder').html(beforeValue);
                                $('#totalDistance').html('0,00');
                                $('#totalPrice').html('0,00');
                                return Swal.fire({
                                    type: 'error',
                                    icon: 'error',
                                    title: '{{__('There was an error calculating delivery')}}',
                                });
                            },
                        });
                    }
                </script>
            @else
                <script type="text/javascript">
                    $('#inputCollectPlace,#inputDeliveryPlace').focus(function(){
                        $('#modalLogin').modal('show');
                        $('#createAccountContent').show();
                        $('#loginContent').hide();
                        $('#modalTitleLogin').html('{{__('Start creating your account!')}}');
                    });
                </script>
            @endif
            <script type="text/javascript">
                $('.orderingTypes > li').click(function (){
                    $('#inputCollectPlace').trigger("click").focus();
                })

            </script>
    @endpush
@endsection

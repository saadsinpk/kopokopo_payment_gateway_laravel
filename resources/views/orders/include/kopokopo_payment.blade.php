<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('public/assets/css/intlTelInput.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/formValidation.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}" />
    <title>Document</title>

    <style>
    body.modal-open {
    overflow: hidden !important;
    position: relative;
    }
    .modal-body {
        height: 45vh;
        overflow-y: unset;
    }
    </style>

</head>
<body>
    <div class="row mx-1 vertical-center">
        <div class="col-12 text-center">
            <h5 style="color: #777777">
                {{ __('Payment Pending') }}<br>
                <small>{{ number_format($order->distance, 3, '.', '') }} {{ setting('distance_unit', 'mi') }} -
                    {!! getPrice($order->total_value) !!}</small>
            </h5>
            <!-- <button data-toggle="modal" data-target="#exampleModal" id="start-payment-button" class="btn btn-sm btn-outline-primary btn-block mb-3"
                target="_blank">{{ __('Pay Now') }}</button> -->
            <div id="payment-container" style="margin: 0 auto;"></div>
            <div id="payment-message" class="hidden"></div>
        </div>
        <div class="container">
                <form id="payment-form">
                    @csrf
                    <img src="{{ asset('img/kopo.jpg') }}" alt="" class="img-fluid center" style="height: 64px;margin-bottom: 20px; text-align: center; display: block;margin: 0 auto;">

                    <input class="form-control" name="first_name" value="{{Auth::user()->name}}" size='4' type='hidden'>

                    <!-- <input id="jquery-intl-phone" type="tel"> -->
                    <div class='form-row row' style="padding: 0px 220px;">
                        <div class='col-xs-12 col-md-12 form-group cvc required'>
                            <label class='control-label' style="text-align:left !important;">Phone</label>
                            <input type="hidden"  class="code" name="phone_number_code"  >
                            <input type="tel" class="form-control phone_flag" value="{{Auth::user()->phone}}" id="phone_number" placeholder="Enter Your Phone" name="number">
                            <input type="hidden" class="form-control number" value="" id="number" name="phone">
                            <!-- <input type="tel" id="jquery-intl-phone" class="form-control" placeholder="Enter Your Phone" name="phone" value="{{Auth::user()->phone}}"> -->
                            <!-- <input id="phone" autocomplete='off' class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{Auth::user()->phone}}" placeholder='Enter Your Phone' type="text"> -->
                        </div>
                    </div>

                    <div class="row" style="margin-right: -15px; margin-left: 5px; justify-content: center;">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-md btn-block" type="submit">{{ __('Pay Now') }}</button>
                        </div>
                    </div>
                </form>
            </div>
    </div>

    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="justify-content: center;">
                    <h5 class="modal-title" id="exampleModalLabel">{{Auth::user()->name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 18px;">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form id="payment-form">
                                @csrf
                                <img src="{{ asset('img/kopo.jpg') }}" alt="" class="img-fluid center" style="height: 64px;margin-bottom: 20px; text-align: center; display: block;margin: 0 auto;">

                                <input class="form-control" name="first_name" value="{{Auth::user()->name}}" size='4' type='hidden'>

                                <!-- <div class='form-row row'> -->
                                    <!-- <div class='col-xs-12 col-md-12 form-group required'> -->
                                        <!-- <label class='control-label'>First Name</label>  -->                                     
                                    <!-- </div> -->
                                <!-- </div> -->

                                <!-- <div class='form-row row'>
                                    <div class='col-xs-12 col-md-12 form-group required'>
                                        <label class='control-label'>Last Name</label> 
                                        <input class='form-control' name="last_name" size='4' type='text'>
                                    </div>
                                </div> -->

                                <input id="jquery-intl-phone" type="tel">
                                <div class='form-row row'>
                                    <div class='col-xs-12 col-md-12 form-group cvc required'>
                                        <label class='control-label'>Phone</label>
                                        <input type="tel" id="jquery-intl-phone" class="form-control" placeholder="Enter Your Phone" name="phone" value="{{Auth::user()->phone}}">
                                        <!-- <input id="phone" autocomplete='off' class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{Auth::user()->phone}}" placeholder='Enter Your Phone' type="text"> -->
                                    </div>
                                </div>

                                <div class="row" style="margin-right: -15px; margin-left: 5px; justify-content: center;">
                                    <div class="col-xs-12">
                                        <button class="btn btn-primary btn-lg btn-block" type="submit">{{ __('Pay Now') }}</button>
                                    </div>
                                </div>
                            </form>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="{{ asset('public/assets/js/intlTelInput.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.6/es6-shim.min.js" integrity="sha512-Dg4/NsM35WYe4Vpj/ZzxsN7K4ifsi6ecw9+VB5rmCntqoQjEu4dQxL6/qQVebHalidCqWiVkWVK59QtJCCjBDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('public/assets/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/InternationalTelephoneInput.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/main.js') }}"></script> 
</html>
   
    
    
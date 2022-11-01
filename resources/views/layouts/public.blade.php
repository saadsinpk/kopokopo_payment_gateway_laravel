<!DOCTYPE html>
<html lang="{{setting('language','en')}}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') | {{ setting('app_name') }}</title>
    <link rel="icon" type="image/png" href="{{$app_logo}}" />
    <!-- General CSS Files -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/@fortawesome/fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">

    <!-- Template CSS -->
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/social-icons.css')}}">
    <link href="{{ asset('css/external.css') }}" rel="stylesheet" type="text/css"/>
    @stack('css')
</head>

<body dir="{{(setting('language_rtl', false) ? 'rtl' : 'ltr')}}">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{route('home')}}"><img src="{{$app_logo}}" title="{{setting('app_name')}}" height="50"> {{setting('app_name')}}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{(\Illuminate\Support\Facades\Route::is('home')?'active':'')}}">
                <a class="nav-link" href="{{route('home')}}">{{__('Place an Order')}}</a>
            </li>
            <li class="nav-item {{(\Illuminate\Support\Facades\Route::is('orders.index')?'active':'')}}">
                <a class="nav-link" href="{{route('orders.index')}}">{{__('My Orders')}}</a>
            </li>
            @can('admin.dashboard')
                <li class="nav-item {{(\Illuminate\Support\Facades\Route::is('admin.dashboard')?'active':'')}}">
                    <a class="nav-link" href="{{route('admin.dashboard')}}">{{__('Admin')}}</a>
                </li>
            @endif
        </ul>
        @if(!auth()->check())
            <button class="btn btn-outline-primary loginAccount" id="">{{__('Login')}}</button>
            &nbsp;
            <button class="btn btn-outline-success createAccount" id="">{{__('Create an Account')}}</button>
        @else
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('Hello')}}, {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{route('home')}}">{{__('Place an Order')}}</a>
                    @can('admin.dashboard')
                        <a class="dropdown-item" href="{{route('admin.dashboard')}}">{{__('Admin Dashboard')}}</a>
                    @endcan
                    <a class="dropdown-item" href="{{route('logout')}}">{{__('Logout')}}</a>
                </div>
            </div>
        @endif
    </div>
</nav>
<div id="app" style="{{(isset($background_image) && $background_image)?"background-image: url('".$background_image."')":''}}">
    <section class="section">
        <div class="container mt-0">
            <div class="row">
                <div class="col-md-10 offset-md-1 mt-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>
    <div class="simple-footer text-center">
        <img src="{{ $app_logo }}" alt="logo" style="max-height: 100px;">
        <br>
        {{ setting('app_name') }} | Copyright  - {{ date('Y') }}<br>
        @if(setting('enable_terms_of_service'))
        <a href="{{route('terms')}}" target="_blank">{{__("Terms of Use")}}</a>
        @endif
        @if(setting('enable_terms_of_service') && setting('enable_privacy_policy'))
            |
        @endif
        @if(setting('enable_privacy_policy'))
            <a href="{{route('privacy')}}" target="_blank">{{__("Privacy Policy")}}</a>
        @endif
    </div>
</div>

@if(!auth()->check())
    <div class="modal" id="modalLogin" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title" id="modalTitleLogin">{{__('You need to login to make orders')}}</h4>
                </div>
                <div class="modal-body">
                    <div id="loginContent">
                        <form method="POST" action="{{ route('login') }}" onsubmit="return false;">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{__('Email')}}</label>
                                <input aria-describedby="emailHelpBlock" id="email" type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                       placeholder="{{__('Input your email')}}" tabindex="1"
                                       value="" autofocus
                                       required>
                                <div class="invalid-feedback">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password" class="control-label">{{__('Password')}}</label>
                                    <div class="float-right">
                                        <a href="{{route('password.request')}}" class="text-small">
                                            {{__('Forgot your password?')}}
                                        </a>
                                    </div>
                                </div>
                                <input aria-describedby="passwordHelpBlock" id="password" type="password"
                                       value=""
                                       placeholder="{{__('Input your password')}}"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}" name="password"
                                       tabindex="2" required>
                                <div class="invalid-feedback">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="">
                                    <input type="hidden" name="remember_me" value="0">
                                    <input type="checkbox" name="remember_me" class="" tabindex="3"
                                           id="remember_me" value="1" checked>
                                    <label for="remember_me">{{__('Remember me')}}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block btnLogin" tabindex="4">
                                    {{__('Login')}}
                                </button>
                            </div>

                            @if(setting('enable_facebook',false) || setting('enable_google',false) || setting('enable_twitter',false) || setting('enable_linkedin',false) || setting('enable_github',false) || setting('enable_gitlab',false) || setting('enable_bitbucket',false))
                                <div class="form-group mt-0 text-center">
                                    <p class="text-center" style="text-transform: uppercase">- {{__('auth.or')}} -</p>
                                    @foreach(['facebook','google','twitter','linkedin','github','gitlab','bitbucket'] as $social)
                                        @if(setting('enable_'.$social,false))
                                            <a href="{{ url('login/'.$social) }}" class="social-button" id="{{$social."-connect"}}">
                                                <i class="fab fa-{{ $social }} mr-3"></i> {{ __('auth.login_'.$social) }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            <div class="mt-5 text-muted text-center">
                                {{__('Don\'t have an account?')}} <a
                                    href="#" class="btn btn-block btn-success createAccount">{{__('Create account')}}</a>
                            </div>
                        </form>
                    </div>
                    <div id="createAccountContent" style="display: none">
                        <form method="POST" action="#" onsubmit="return false;">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">{{ __('Name') }}:</label><span
                                            class="text-danger">*</span>
                                        <input id="firstName" type="text"
                                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               name="name"
                                               placeholder="{{ __('Input your name') }}" value="{{ old('name') }}"
                                               required>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">{{ __('Email') }}:</label><span
                                            class="text-danger">*</span>
                                        <input id="email" type="email"
                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               placeholder="{{ __('Input your email') }}" name="email"
                                               value="{{ old('email') }}"
                                               required >
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">{{ __('Phone') }}:</label><span
                                            class="text-danger">*</span>
                                        <input id="phone" type="text"
                                               class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                               placeholder="{{ __('Input your phone') }}" name="phone"
                                               value="{{ old('phone') }}"
                                               required>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="control-label">{{ __('Password') }}
                                            :</label><span
                                            class="text-danger">*</span>
                                        <input id="password" type="password"
                                               class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}"
                                               placeholder="{{ __('Input your password') }}" name="password" required>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation"
                                               class="control-label">{{ __('Password Confirmation') }}:</label><span
                                            class="text-danger">*</span>
                                        <input id="password_confirmation" type="password" placeholder="{{ __('Password Confirmation') }}"
                                               class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid': '' }}"
                                               name="password_confirmation" required>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password_confirmation') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    @if(setting('enable_terms_of_service') || setting('enable_privacy_policy'))
                                        <div class="form-group">
                                            {{__('Creating your account you agree to our')." "}}
                                            @if(setting('enable_terms_of_service'))
                                                <a href="{{ route('terms') }}" target="_blank">{{ __('Terms of Use') }}</a>
                                            @endif
                                            @if(setting('enable_terms_of_service') && setting('enable_privacy_policy'))
                                                {{" ".__('and')." "}}
                                            @endif
                                            @if(setting('enable_privacy_policy'))
                                                <a href="{{ route('privacy') }}" target="_blank">{{ __('Privacy Policy') }}</a>
                                            @endif
                                            {{'.'}}
                                        </div>
                                    @endif
                                    <button type="button" class="btn btn-primary btn-lg btn-block btnCreateAccount">
                                        {{__('Create Account')}}
                                    </button>
                                    @if(setting('enable_facebook',false) || setting('enable_google',false) || setting('enable_twitter',false) || setting('enable_linkedin',false) || setting('enable_github',false) || setting('enable_gitlab',false) || setting('enable_bitbucket',false))
                                        <div class="form-group mt-0 text-center">
                                            <p class="text-center" style="text-transform: uppercase">- {{__('auth.or')}} -</p>
                                            @foreach(['facebook','google','twitter','linkedin','github','gitlab','bitbucket'] as $social)
                                                @if(setting('enable_'.$social,false))
                                                    <a href="{{ url('login/'.$social) }}" class="social-button" id="{{$social."-connect"}}">
                                                        <i class="fab fa-{{ $social }} mr-3"></i> {{ __('auth.login_'.$social) }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-5 text-muted text-center">
                                {{__('Already have an account?')}} <a
                                    href="#" class="loginAccount">{{__('Log in')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
@endif

<!-- General JS Scripts -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5.0.6/bootstrap-4.min.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css" integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js" integrity="sha512-L3moecKIMM1UtlzYZdiGlge2+bugLObEFLOFscaltlJ82y0my6mTUugiz6fQiSc5MaS7Ce0idFJzabEAl43XHg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js"></script>

<style>
    .iti{
        width: 100% !important;
    }
    .iti #inputPhone{
        padding: 1rem 0.75rem !important;
        padding-left: 95px !important;
    }
</style>

<script type="text/javascript">

    @if(!auth()->check())
        $('#modalLogin').modal({
            keyboard: true,
            show: false,
            focus: true,
        });
        $('.loginAccount').click(function () {
            $('#modalLogin').modal('show');
            $('#createAccountContent').hide();
            $('#loginContent').show();
            $('#modalTitleLogin').html('{{__('Login')}}');
        });
        $('.createAccount').click(function () {
            $('#modalLogin').modal('show');
            $('#createAccountContent').show();
            $('#loginContent').hide();
            $('#modalTitleLogin').html('{{__('Create Account')}}');
        });

        var inputPhone = document.querySelector("#phone");
        var iti2 = window.intlTelInput(inputPhone, {
            utilsScript:"https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.min.js",
            separateDialCode:true,
        });


        $('.btnLogin').click(function(){
            let $this = $(this);
            let beforeVal = $this.html();
            $this.attr('disabled', true);
            $this.html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{url('login')}}',
                type: 'POST',
                dataType:'json',
                data: $this.parents('form').first().serialize(),
                success: function (data) {
                    Swal.fire({
                        text: '{{__('Login Successful')}}',
                        type: 'success',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.value) {
                            window.location.reload();
                        }
                    });
                },
                error: function (data) {

                    data = data.responseJSON;

                    $this.attr('disabled', false);
                    $this.html(beforeVal);
                    Swal.fire({
                        text: data.message ?? '{{__('An error occurred doing your login, try again later.')}}',
                        icon: 'error',
                        type: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });

                }
            });

        });

        $('.btnCreateAccount').click(function(){
            let $this = $(this);
            let beforeVal = $this.html();
            $this.attr('disabled', true);
            $this.html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{url('register')}}',
                type: 'POST',
                dataType:'json',
                data: $this.parents('form').first().serialize(),
                success: function (data) {
                    Swal.fire({
                        text: data.message,
                        type: 'success',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.value) {
                            window.location.reload();
                        }
                    });
                },
                error: function (data) {
                    data = data.responseJSON;
                    $this.attr('disabled', false);
                    $this.html(beforeVal);
                    Swal.fire({
                        text: data.message??'{{__('An error occurred while creating your account, try again later.')}}',
                        icon: 'error',
                        type: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });
    @else
        @include('vendor.notifications.init_firebase')

        const messaging = firebase.messaging();



        function initFirebaseMessagingRegistration() {
            messaging.requestPermission().then(function () {
                return messaging.getToken()
            }).then(function(token) {
                $.ajax({
                    url: '{{url('api/notifications/update_token')}}',
                    type: 'POST',
                    dataType:'json',
                    data: {
                        api_token:"{{ auth()->user()->api_token }}",
                        firebase_token:token
                    },
                    success: function (data) {
                        console.log(data);
                    }
                });

            }).catch(function (err) {
                console.log(`Token Error :: ${err}`);
            });
        }

        initFirebaseMessagingRegistration();
        messaging.onMessage(function({data:{body,title}}){
            new Notification(title, {body});
        });
    @endif

</script>



<!-- Page Specific JS File -->

@stack('scripts')

</body>
</html>

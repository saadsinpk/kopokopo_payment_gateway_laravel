@extends('layouts.auth.auth')
@section('title')
    {{ __('Register') }}
@endsection
@section('content')
    <style>
        .iti {
            width: 100% !important;
        }

        .iti #inputPhone {
            padding: 1rem 0.75rem !important;
            padding-left: 95px !important;
        }
    </style>

    <div class="card card-primary">
        <div class="card-header">
            <h4>{{ __('Register') }}</h4>
        </div>

        <div class="card-body pt-1">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">{{ __('Name') }}:</label><span class="text-danger">*</span>
                            <input id="firstName" type="text"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                tabindex="1" placeholder="{{ __('Enter your name') }}" value="{{ old('name') }}"
                                autofocus required>
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">{{ __('Phone') }}:</label><span class="text-danger">*</span>
                            <input id="phone" type="phone"
                                class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Enter your phone') }}" name="phone" tabindex="1"
                                value="{{ old('phone') }}" required autofocus>
                            <div class="invalid-feedback">
                                {{ $errors->first('phone') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="email">{{ __('Email') }}:</label><span class="text-danger">*</span>
                            <input id="email" type="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Enter your email') }}" name="email" tabindex="1"
                                value="{{ old('email') }}" required autofocus>
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="control-label">{{ __('Password') }}
                                :</label><span class="text-danger">*</span>
                            <input id="password" type="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="{{ __('Enter your password') }}" name="password" tabindex="2" required>
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
                            <input id="password_confirmation" type="password"
                                placeholder="{{ __('Confirm your password') }}"
                                class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                name="password_confirmation" tabindex="2">
                            <div class="invalid-feedback">
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        @if (setting('enable_terms_of_service') || setting('enable_privacy_policy'))
                            <div class="form-group">
                                {{ __('Creating an account means you agree to our ') }}
                                @if (setting('enable_terms_of_service'))
                                    <a href="{{ route('terms') }}" target="_blank">{{ __('Terms of Service') }}</a>
                                @endif
                                @if (setting('enable_terms_of_service') && setting('enable_privacy_policy'))
                                    {{ ' ' . __('and') . ' ' }}
                                @endif
                                @if (setting('enable_privacy_policy'))
                                    <a href="{{ route('privacy') }}" target="_blank">{{ __('Privacy Policy') }}</a>
                                @endif
                                {{ '.' }}
                            </div>
                        @endif
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                    @if (setting('enable_facebook', false) ||
                        setting('enable_google', false) ||
                        setting('enable_twitter', false) ||
                        setting('enable_linkedin', false) ||
                        setting('enable_github', false) ||
                        setting('enable_gitlab', false) ||
                        setting('enable_bitbucket', false))
                        <div class="col-12 mt-0 text-center">
                            <p class="text-center" style="text-transform: uppercase">- {{ __('auth.or') }} -</p>
                            @foreach (['facebook', 'google', 'twitter', 'linkedin', 'github', 'gitlab', 'bitbucket'] as $social)
                                @if (setting('enable_' . $social, false))
                                    <a href="{{ url('login/' . $social) }}" class="social-button"
                                        id="{{ $social . '-connect' }}">
                                        <i class="fab fa-{{ $social }} mr-3"></i> {{ __('auth.login_' . $social) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div class="mt-5 text-muted text-center">
        {{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('Sign In') }}</a>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/css/intlTelInput.css"
        integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js"
        integrity="sha512-L3moecKIMM1UtlzYZdiGlge2+bugLObEFLOFscaltlJ82y0my6mTUugiz6fQiSc5MaS7Ce0idFJzabEAl43XHg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var inputPhone = document.querySelector("#phone");
        var iti2 = window.intlTelInput(inputPhone, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/utils.min.js",
            separateDialCode: true,
        });
    </script>

@endsection

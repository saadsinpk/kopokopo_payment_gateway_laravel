@extends('layouts.auth.auth')
@section('title')
    {{ __('Login') }}
@endsection
@section('content')
    <div class="card card-primary">
        <div class="card-header"><h4>{{ __('Login') }}</h4></div>

        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger p-0">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <label for="email">{{__('Email')}}</label>
                    <input aria-describedby="emailHelpBlock" id="email" type="email"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                           placeholder="{{__('Enter Your Email')}}" tabindex="1"
                           value="{{ (Cookie::get('email') !== null) ? Cookie::get('email') : old('email') }}" autofocus
                           required>
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">{{__('Password')}}</label>
                        <div class="float-right">
                            <a href="{{ route('password.request') }}" class="text-small">
                                {{__('Forgot Password?')}}
                            </a>
                        </div>
                    </div>
                    <input aria-describedby="passwordHelpBlock" id="password" type="password"
                           value="{{ (Cookie::get('password') !== null) ? Cookie::get('password') : null }}"
                           placeholder="{{__('Enter Your Password')}}"
                           class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}" name="password"
                           tabindex="2" required>
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3"
                               id="remember"{{ (Cookie::get('remember') !== null) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember">{{__('Remember Me')}}</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
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
            </form>
        </div>
    </div>
    <div class="mt-5 text-muted text-center">
        {{__('Don\'t have an account?')}} <a
            href="{{ route('register') }}">{{__('Register')}}</a>
    </div>
@endsection

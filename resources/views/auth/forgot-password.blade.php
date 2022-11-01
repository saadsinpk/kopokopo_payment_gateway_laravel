@extends('layouts.auth.auth')
@section('title')
    {{__('Forgot Password')}}
@endsection
@section('content')
    <div class="card card-primary">
        <div class="card-header"><h4>{{__('Reset Password')}}</h4></div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{__('Email')}}</label>
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" tabindex="1" value="{{ old('email') }}" autofocus required>
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        {{__('Send Reset Link')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-5 text-muted text-center">
        {{__('Recalled your login info?')}} <a href="{{ route('login') }}">{{__('Login')}}</a>
    </div>
@endsection

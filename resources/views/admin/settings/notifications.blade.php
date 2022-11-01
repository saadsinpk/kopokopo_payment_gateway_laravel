@extends('layouts.app')
@section('title')
    {{ __('Notifications Settings') }}
@endsection
@push('page_css')
    <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
@endpush
@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-md-4">
                    @include('layouts.admin.settings.sidebar')
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-12">
                                <h4>{{ __('Notifications Settings') }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => route('admin.settings.saveSettings'), 'method' => 'patch','enctype' => 'multipart/form-data']) !!}
                                @include('flash::message')
                                @include('stisla-templates::common.errors')
                                <h5 class="col-12 pb-4">
                                    <i class="mr-4 fas fa-comment-dots"></i> {!! __('Firebase Settings') !!}
                                </h5>
                                <!-- 'Boolean enable_terms_of_service Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_firebase', __('Enable Firebase Notifications'),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_firebase', 0) !!}
                                            {!! Form::checkbox('enable_firebase', 1, setting('enable_firebase', false)) !!}
                                            <span class="ml-2">{!! trans('Enable Firebase Notifications') !!}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('firebase_serverkey', trans("Cloud Messaging Key"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('firebase_serverkey', setting('firebase_serverkey'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase cloud messaging key")]) !!}
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('firebase_apikey', trans("API Key"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::text('firebase_apikey', setting('firebase_apikey'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase api key")]) !!}
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    {!! Form::label('firebase_authdomain', trans("Auth Domain"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('firebase_authdomain', setting('firebase_authdomain'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase auth domain")]) !!}
                                    </div>
                                </div>
                                <!--<div class="form-group row ">
                                    {!! Form::label('firebase_databaseurl', trans("Database URL"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('firebase_databaseurl', setting('firebase_databaseurl'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase database url")]) !!}
                                    </div>
                                </div>-->
                                <div class="form-group row ">
                                    {!! Form::label('firebase_projectid', trans("Firebase Project Id"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('firebase_projectid', setting('firebase_projectid'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase messaging sender id")]) !!}
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    {!! Form::label('firebase_storagebucket', trans("Storage Bucket"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('firebase_storagebucket', setting('firebase_storagebucket'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase storage bucket")]) !!}
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    {!! Form::label('firebase_messagingsenderid', trans("Messaging Sender Id"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('firebase_messagingsenderid', setting('firebase_messagingsenderid'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase messaging sender id")]) !!}
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    {!! Form::label('firebase_appid', trans("App Id"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('firebase_appid', setting('firebase_appid'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase app id")]) !!}
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    {!! Form::label('firebase_measurementid', trans("Measurement Id"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('firebase_measurementid', setting('firebase_measurementid'),  ['class' => 'form-control','placeholder'=>  __("Input your firebase measurement id")]) !!}
                                    </div>
                                </div>
                                <hr>
                                <h5 class="col-12 pb-4">
                                    <i class="mr-4 fas fa-envelope"></i> {!! __('SMTP Driver') !!}
                                </h5>
                                <!-- 'Boolean enable_terms_of_service Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_mail', __('Enable Mail Notifications'),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_mail', 0) !!}
                                            {!! Form::checkbox('enable_mail', 1, setting('enable_mail', false)) !!}
                                            <span class="ml-2">{!! trans('Enable Email Notifications') !!}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- mail_host Field -->
                                <div class="form-group row ">
                                    {!! Form::label('mail_host', trans("Host"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('mail_host', setting('mail_host'),  ['class' => 'form-control','placeholder'=>  __("Input your mail host")]) !!}

                                    </div>
                                </div>

                                <!-- mail_port Field -->
                                <div class="form-group row ">
                                    {!! Form::label('mail_port', trans("Port"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('mail_port', setting('mail_port'),  ['class' => 'form-control','placeholder'=>  trans("Input mail port")]) !!}
                                    </div>
                                </div>

                                <!-- Lang Field -->
                                <div class="form-group row ">
                                    {!! Form::label('mail_encryption', trans("Encryptation"),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::select('mail_encryption', ['tls'=>'TLS', 'ssl'=>'SSL'], setting('mail_encryption'), ['class' => 'select2 form-control']) !!}
                                        <div class="form-text text-muted">{{ trans("Select your mail encryptation") }}</div>
                                    </div>
                                </div>

                                <!-- mail_username Field -->
                                <div class="form-group row">
                                    {!! Form::label('mail_username', trans("Username"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('mail_username', setting('mail_username'),  ['class' => 'form-control','placeholder'=>  trans("Input your mail username")]) !!}
                                    </div>
                                </div>

                                <!-- mail_password Field -->
                                <div class="form-group row ">
                                    {!! Form::label('mail_password', trans("Password"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        @if(env('APP_DEMO', false))
                                            <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                        @else
                                            {!! Form::password('mail_password',  ['class' => 'form-control','placeholder'=>  trans("Input your mail password")]) !!}
                                        @endif
                                    </div>
                                </div>
                                <!-- mail_from_address Field -->
                                <div class="form-group row ">
                                    {!! Form::label('mail_from_address', trans("From address"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('mail_from_address', setting('mail_from_address'),  ['class' => 'form-control','placeholder'=>  trans("Input your mail from address")]) !!}
                                    </div>
                                </div>

                                <!-- mail_from_name Field -->
                                <div class="form-group row ">
                                    {!! Form::label('mail_from_name', trans("From name"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('mail_from_name', setting('mail_from_name'),  ['class' => 'form-control','placeholder'=>  trans("Input your mail from name")]) !!}
                                    </div>
                                </div>

                                <div class="form-group text-center pt-4">
                                    {!! Form::button(__('Save'), ['type' => 'submit','class' => 'btn btn-primary']) !!}
                                    <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                </div>


                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
@endpush

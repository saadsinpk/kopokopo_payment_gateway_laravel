@extends('layouts.app')
@section('title')
    {{ __('Legal Settings') }}
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
                            <h4>{{ __('Legal Settings') }}</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => route('admin.settings.saveSettings'), 'method' => 'patch']) !!}
                                @include('flash::message')
                                @include('stisla-templates::common.errors')

                                <!-- 'Boolean enable_terms_of_service Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_terms_of_service', __('Enable Terms of Service'),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_terms_of_service', null) !!}
                                            {!! Form::checkbox('enable_terms_of_service', 1, setting('enable_terms_of_service', false)) !!}
                                            <span class="ml-2">{!! trans('Check to enable terms of service') !!}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- terms_of_service Field -->
                                <div class="form-group">
                                    {!! Form::label('terms_of_service', __("Terms of Service"), ['class' => 'control-label text-right']) !!}

                                        {!! Form::textarea('terms_of_service', setting('terms_of_service',''),  ['class' => 'form-control','placeholder'=>  trans("Write your terms of service"),'style' => 'min-height:250px']) !!}
                                        <div class="form-text text-muted">
                                            {{ __("Terms of service of your application") }}
                                        </div>
                                </div>

                                <!-- 'Boolean enable_privacy_policy Field' -->
                                <div class="form-group row">
                                    {!! Form::label('enable_privacy_policy', __('Enable Privacy Policy'),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="checkbox icheck">
                                        <label class="w-100 ml-2 form-check-inline">
                                            {!! Form::hidden('enable_privacy_policy', null) !!}
                                            {!! Form::checkbox('enable_privacy_policy', 1, setting('enable_privacy_policy', false)) !!}
                                            <span class="ml-2">{!! trans('Check to enable privacy policy') !!}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- privacy_policy Field -->
                                <div class="form-group">
                                    {!! Form::label('privacy_policy', __("Privacy Policy"), ['class' => 'control-label text-right']) !!}

                                    {!! Form::textarea('privacy_policy', setting('privacy_policy',''),  ['class' => 'form-control','placeholder'=>  trans("Write your privacy policy"),'style' => 'min-height:250px']) !!}
                                    <div class="form-text text-muted">
                                        {{ __("Privacy Policy of your application") }}
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
    <script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript">
        CKEDITOR.replace('terms_of_service');
        CKEDITOR.replace('privacy_policy');
    </script>

@endpush

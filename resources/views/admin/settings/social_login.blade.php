@extends('layouts.app')
@section('title')
    {{ __('Social Login Settings') }}
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
                                <h4>{{ __('Social Login Settings') }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => route('admin.settings.saveSettings'), 'method' => 'patch','enctype' => 'multipart/form-data']) !!}
                                @include('flash::message')
                                @include('stisla-templates::common.errors')

                                @foreach(['facebook','google','twitter'] as $social)
                                    <h5 class="col-12 pb-4"><i class="mr-4 fab fa-{{$social}}"></i>  {{ucfirst($social)}}</h5>

                                    <!-- 'Boolean enable_facebook Field' -->
                                    <div class="form-group row">
                                        {!! Form::label('enable_'.$social, __('Enable')." ".ucfirst($social),['class' => 'col-2 control-label text-right']) !!}
                                        <div class="checkbox icheck">
                                            <label class="w-100 ml-2 form-check-inline">
                                                {!! Form::hidden('enable_'.$social, 0) !!}
                                                {!! Form::checkbox('enable_'.$social, 1, setting('enable_'.$social, false)) !!}
                                                <span class="ml-2">{!! trans('Check to enable '.$social.' social login') !!}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- facebook_app_id Field -->
                                    <div class="form-group row">
                                        {!! Form::label($social.'_app_id', ucfirst($social).' App ID', ['class' => 'col-4 control-label text-right']) !!}
                                        <div class="col-8">
                                            {!! Form::text($social.'_app_id', setting($social.'_app_id'),  ['class' => 'form-control','placeholder'=>  __('Input your '.ucfirst($social).' App Id')]) !!}
                                        </div>
                                    </div>

                                    <!-- facebook_app_secret Field -->
                                    <div class="form-group row">
                                        {!! Form::label($social.'_app_secret', $social.'App Secret', ['class' => 'col-4 control-label text-right']) !!}
                                        <div class="col-8">
                                            @if(env('APP_DEMO', false))
                                                <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                            @else
                                                {!! Form::text($social.'_app_secret', setting($social.'_app_secret'),  ['class' => 'form-control','placeholder'=>  __('Input your '.$social.' App Secret')]) !!}
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach

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

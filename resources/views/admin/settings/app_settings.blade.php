@extends('layouts.app')
@section('title')
    {{ __('App Settings') }}
@endsection
@push('page_css')
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
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
                            <h4>{{ __('App Settings') }}</h4>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => route('admin.settings.saveSettings'), 'method' => 'patch','files' =>true]) !!}
                            @include('flash::message')
                            @include('stisla-templates::common.errors')

                            <!-- header_text Field -->
                            <div class="form-group row">
                                {!! Form::label('header_text', __("Header Text"), ['class' => 'col-4 control-label text-right']) !!}
                                <div class="col-8">
                                    {!! Form::text('header_text', setting('header_text'),  ['class' => 'form-control','placeholder'=>  trans("Promotional Header Text")]) !!}
                                    <div class="form-text text-muted">
                                        {{ __("Promotional header text") }}
                                    </div>
                                </div>
                            </div>
                            <!-- subtitle Field -->
                            <div class="form-group row">
                                {!! Form::label('subheader_text', __("Sub Header Text"), ['class' => 'col-4 control-label text-right']) !!}
                                <div class="col-8">
                                    {!! Form::text('subheader_text', setting('subheader_text'),  ['class' => 'form-control','placeholder'=>  trans("Promotional Sub-Header Text")]) !!}
                                    <div class="form-text text-muted">
                                        {{ __("Promotional sub-header Text") }}
                                    </div>
                                </div>
                            </div>

                            <!-- App Logo Field -->
                            <div class="form-group row">
                                {!! Form::label('background_image', __("Background Image"), ['class' => 'col-4 control-label text-right']) !!}
                                <div class="col-8">
                                    <div class="input-logo">
                                        <input type="file" name="background_image" class="form-control" id="inputLogo" style="" accept="image/*" onchange="loadFile(event)">
                                        <div class="form-text text-muted">
                                            {{__('Leave empty to not change')}}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img id="imagePreview" src="{{$background_image}}" alt="Image Preview" style="max-width: 50%;display: {{($background_image)?'inline-block':'none'}}" />
                                    </div>

                                </div>
                            </div>

                            <hr>

                            <!-- distance_unit Field -->
                            <div class="form-group row ">
                                {!! Form::label('distance_unit', trans('Distance Unit'), ['class' => 'col-4 control-label text-right']) !!}
                                <div class="col-8">
                                    {!! Form::select(
                                        'distance_unit',
                                        ['mi' => trans('Mile'), 'km' => trans('Kilometer')],
                                        setting('distance_unit', 'mi'),
                                        [
                                            'class' => 'select2 form-control',
                                        ],
                                    ) !!}
                                </div>
                            </div>

                            <!-- app_name Field -->
                            <div class="form-group row">
                                {!! Form::label('google_maps_key', __("Google Maps Key"), ['class' => 'col-4 control-label text-right']) !!}
                                <div class="col-8">
                                    @if(env('APP_DEMO', false))
                                        <input type="text" class="form-control" value="{{__('Hidden in demo version')}}" disabled="disabled">
                                    @else
                                        {!! Form::text('google_maps_key', setting('google_maps_key'),  ['class' => 'form-control','placeholder'=>  trans("Google Maps API Key")]) !!}
                                    @endif
                                    <div class="form-text text-muted">
                                        {{ __("Google Maps API Key") }}
                                    </div>
                                </div>
                            </div>

                            <!-- maximum nearby Field -->
                            <div class="form-group row">
                                {!! Form::label('maximum_allowed_distance', __("Maximum allowed distance"), ['class' => 'col-4 control-label text-right']) !!}
                                <div class="col-8">
                                    {!! Form::text('maximum_allowed_distance', setting('maximum_allowed_distance'),  ['class' => 'form-control','placeholder'=>  trans("Maximum allowed distance from pick-up location")]) !!}
                                    <div class="form-text text-muted">
                                        {{ __("Maximum allowed courier distance from the pick-up point to be showed") }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 text-center">
                                    <h4>Theme Options</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title control-label">{{ __('Light Theme') }}</h5>
                                            <div class="form-group row">
                                                {!! Form::label('main_color', __('Main Color'), [
                                                    'class' => 'col-4 control-label text-right',
                                                ]) !!}
                                                <div class="col-8">
                                                    <div id="main-colorpicker" class="input-group colorpicker-component">
                                                        {!! Form::text('main_color', setting('main_color'), [
                                                            'class' => 'form-control',
                                                            'placeholder' => __('Enter the color'),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                        <div class="input-group-append ">
                                                            <span class="input-group-addon input-group-text"><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text text-muted">
                                                        {{ trans('Enter the main color') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('secondary_color', __('Secondary Color'), [
                                                    'class' => 'col-4 control-label text-right',
                                                ]) !!}
                                                <div class="col-8">
                                                    <div id="main-colorpicker" class="input-group colorpicker-component">
                                                        {!! Form::text('secondary_color', setting('secondary_color'), [
                                                            'class' => 'form-control',
                                                            'placeholder' => __('Enter the color'),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                        <div class="input-group-append ">
                                                            <span class="input-group-addon input-group-text"><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text text-muted">
                                                        {{ trans('Enter the secondary color') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('highlight_color', __('Highlight Color'), [
                                                    'class' => 'col-4 control-label text-right',
                                                ]) !!}
                                                <div class="col-8">
                                                    <div id="main-colorpicker" class="input-group colorpicker-component">
                                                        {!! Form::text('highlight_color', setting('highlight_color'), [
                                                            'class' => 'form-control',
                                                            'placeholder' => __('Enter the color'),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                        <div class="input-group-append ">
                                                            <span class="input-group-addon input-group-text"><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        {{ trans('Enter the highlight color') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('background_color', __('Background Color'), [
                                                    'class' => 'col-4 control-label text-right',
                                                ]) !!}
                                                <div class="col-8">
                                                    <div id="main-colorpicker" class="input-group colorpicker-component">
                                                        {!! Form::text('background_color', setting('background_color'), [
                                                            'class' => 'form-control',
                                                            'placeholder' => __('Enter the color'),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                        <div class="input-group-append ">
                                                            <span class="input-group-addon input-group-text"><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        {{ trans('Enter the background color') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-secondary text-dark">
                                        <div class="card-body">
                                            <h5 class="card-title control-label">{{ __('Dark Theme') }}</h5>
                                            <div class="form-group row">
                                                {!! Form::label('main_color_dark_theme', __('Main Color'), [
                                                    'class' => 'col-4 control-label text-right',
                                                ]) !!}
                                                <div class="col-8">
                                                    <div id="main-colorpicker" class="input-group colorpicker-component">
                                                        {!! Form::text('main_color_dark_theme', setting('main_color_dark_theme'), [
                                                            'class' => 'form-control',
                                                            'placeholder' => __('Enter the color'),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                        <div class="input-group-append ">
                                                            <span class="input-group-addon input-group-text"><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        {{ trans('Enter the main color') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('secondary_color_dark_theme', __('Secondary Color'), [
                                                    'class' => 'col-4 control-label text-right',
                                                ]) !!}
                                                <div class="col-8">
                                                    <div id="main-colorpicker" class="input-group colorpicker-component">
                                                        {!! Form::text('secondary_color_dark_theme', setting('secondary_color_dark_theme'), [
                                                            'class' => 'form-control  value="transparent"',
                                                            'placeholder' => __('Enter the color'),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                        <div class="input-group-append ">
                                                            <span class="input-group-addon input-group-text"><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        {{ trans('Enter the secondary color') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('highlight_color_dark_theme', __('Highlight Color'), [
                                                    'class' => 'col-4 control-label text-right',
                                                ]) !!}
                                                <div class="col-8">
                                                    <div id="main-colorpicker" class="input-group colorpicker-component">
                                                        {!! Form::text('highlight_color_dark_theme', setting('highlight_color_dark_theme'), [
                                                            'class' => 'form-control',
                                                            'placeholder' => __('Enter the color'),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                        <div class="input-group-append ">
                                                            <span class="input-group-addon input-group-text"><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        {{ trans('Enter the highlight color') }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                {!! Form::label('background_color_dark_theme', __('Background Color'), [
                                                    'class' => 'col-4 control-label text-right',
                                                ]) !!}
                                                <div class="col-8">
                                                    <div id="main-colorpicker" class="input-group colorpicker-component">
                                                        {!! Form::text('background_color_dark_theme', setting('background_color_dark_theme'), [
                                                            'class' => 'form-control',
                                                            'placeholder' => __('Enter the color'),
                                                            'autocomplete' => 'off',
                                                        ]) !!}
                                                        <div class="input-group-append ">
                                                            <span class="input-group-addon input-group-text"><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        {{ trans('Enter the background color') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="form-group text-center pt-4">
                                {!! Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                <a href="{{ route('admin.dashboard') }}"
                                    class="btn btn-secondary">{{ __('Cancel') }}</a>
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
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script type="text/javascript">
        $(".colorpicker-component, input[name$='color']").colorpicker({
            format: 'hex',
            horizontal: true
        });

        var output = document.getElementById('imagePreview');
        var loadFile = function(event) {
            if(event.target.files.length > 0) {
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
            URL.revokeObjectURL(output.src) // free memory
        }
            output.style.display = 'block';
        }else{
            @if($background_image)
            output.src = "{{$background_image}}";
            output.style.display = 'block';
            @endif
        }
        };
    </script>
@endpush
@push('scripts')


@endpush

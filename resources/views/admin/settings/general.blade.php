@extends('layouts.app')
@section('title')
    {{ __('General Settings') }}
@endsection
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
                            <div class="col-6">
                                <h4>{{ __('General Settings') }}</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="nav-link pt-1 float-right" href="{{route('admin.settings.clear_cache')}}"><i class="fas fa-trash"></i> {{trans('Clear cache')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => route('admin.settings.saveSettings'), 'method' => 'patch','enctype' => 'multipart/form-data']) !!}
                                @include('flash::message')
                                @include('stisla-templates::common.errors')
                                <!-- App Logo Field -->
                                <div class="form-group row">
                                    {!! Form::label('app_logo', __("Logo"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <div class="input-logo">
                                            <input type="file" name="app_logo" class="form-control" id="inputLogo" style="" accept="image/*" onchange="loadFile(event)">
                                            <div class="form-text text-muted">
                                                {{__('Leave empty to not change')}}
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <img id="imagePreview" src="{{$app_logo}}" alt="Image Preview" style="max-width: 50%" />
                                        </div>

                                    </div>
                                </div>
                                <!-- app_name Field -->
                                <div class="form-group row">
                                    {!! Form::label('app_name', __("Application name"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::text('app_name', setting('app_name'),  ['class' => 'form-control','placeholder'=>  trans("Your Application Name")]) !!}
                                        <div class="form-text text-muted">
                                            {{ __("The name of your application") }}
                                        </div>
                                    </div>
                                </div>

                                <!-- menu_type Color Field -->
                                <div class="form-group row ">
                                    {!! Form::label('sidebar_default_type', trans("Sidebar Default Type"),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::select('sidebar_default_type',
                                        [
                                        '' => __('Sidebar Expanded'),
                                        'sidebar-mini' => __('Sidebar mini'),

                                        ]
                                        , setting('sidebar_default_type'), ['class' => 'select2 form-control']) !!}
                                        <div class="form-text text-muted">{{ __("Sidebar default style on page load") }}</div>
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    {!! Form::label('language', trans("Language"),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::select('language',getAvailableLanguagesArray(), setting('language','en'), ['class' => 'select2 form-control']) !!}
                                        <div class="form-text text-muted">{{ __("Application default language") }}</div>
                                    </div>
                                </div>

                                 <div class="form-group row ">
                                    {!! Form::label('language_rtl', trans("Language RTL"),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <input type="hidden" name="language_rtl" value="0">
                                        {!! Form::checkbox('language_rtl', '1', setting('language_rtl'), ['id' => 'language_rtl']) !!}
                                        <div class="form-text text-muted">{{ trans("Check if this language is written and read from right-to-left") }}</div>
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    {!! Form::label('timezone', trans("Timezone"),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::select('timezone',getAvailableTimezonesArray(), setting('timezone','UTC'), ['class' => 'select2 form-control']) !!}
                                        <div class="form-text text-muted">{{ __("Application default timezone") }}</div>
                                    </div>
                                </div>


                                <!-- blocked_ips Field -->
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
    <script type="text/javascript">
        var output = document.getElementById('imagePreview');
        var loadFile = function(event) {
            if(event.target.files.length > 0) {
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function () {
                    URL.revokeObjectURL(output.src) // free memory
                }
                output.style.display = 'block';
            }else{
                output.src = "{{$app_logo}}";
                output.style.display = 'block';
            }
        };

    </script>

@endpush

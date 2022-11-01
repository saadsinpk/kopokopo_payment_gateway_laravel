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
                                <h4>{{ __('Currency Settings') }}</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="nav-link pt-1 float-right" href="{{route('admin.settings.clear_cache')}}"><i class="fas fa-trash"></i> {{trans('Clear cache')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => route('admin.settings.saveSettings'), 'method' => 'patch','enctype' => 'multipart/form-data']) !!}
                                @include('flash::message')
                                @include('stisla-templates::common.errors')
                                <!-- currency Color Field -->
                                <div class="form-group row ">
                                    {!! Form::label('currency', trans("Currency"),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::select('currency',$currencies, setting('currency','USD'), ['class' => 'select2 form-control']) !!}
                                    </div>
                                </div>


                                <!-- blocked_ips Field -->
                                <div class="form-group row ">
                                    {!! Form::label('currency_right', trans("Currency Right"),['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        <input type="hidden" name="currency_right" value="0">
                                        {!! Form::checkbox('currency_right', '1', setting('currency_right'), ['id' => 'currency_right']) !!}
                                        <div class="form-text text-muted">{{ trans("Check if this currency is placed on right of the number") }}</div>
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

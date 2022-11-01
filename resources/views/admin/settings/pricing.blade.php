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
                                <h4>{{ __('Pricing') }}</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="nav-link pt-1 float-right" href="{{route('admin.settings.clear_cache')}}"><i class="fas fa-trash"></i> {{trans('Clear cache')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['url' => route('admin.settings.saveSettings'), 'method' => 'patch','enctype' => 'multipart/form-data']) !!}
                                @include('flash::message')
                                @include('stisla-templates::common.errors')

                                <div class="form-group row">
                                    {!! Form::label('base_price', __("Base Price"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::number('base_price', setting('base_price'),  ['class' => 'form-control','placeholder'=>  trans("Base Price"),'min' => 0,'step' => 0.01]) !!}
                                        <div class="form-text text-muted">
                                            {{ __("Minimum price of the order") }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('base_distance', __("Base Distance"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::number('base_distance', setting('base_distance'),  ['class' => 'form-control','placeholder'=>  trans("Base Distance"),'min' => 0,'step' => 0.01]) !!}
                                        <div class="form-text text-muted">
                                            {{ __("Distance that is paid by the minimum price without additional pricing factor") }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('additional_distance_pricing', __("Additional Distance Price"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::number('additional_distance_pricing', setting('additional_distance_pricing'),  ['class' => 'form-control','placeholder'=>  trans('price by ').setting('distance_unit','mi'),'min' => 0,'step' => 0.01]) !!}
                                        <div class="form-text text-muted">
                                            {{ __("Additional price for distance surplus the base distance") }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('return_distance_pricing', __("Return Distance Price"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::number('return_distance_pricing', setting('return_distance_pricing'),  ['class' => 'form-control','placeholder'=>  trans('price by ').setting('distance_unit','mi'),'min' => 0,'step' => 0.01]) !!}
                                        <div class="form-text text-muted">
                                            {{ __("If the customer order to return to the collect place after the last delivery the distance between the last place and the collect place will be multiplied by this price") }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('additional_stop_tax', __("Additional Stop Tax"), ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::number('additional_stop_tax', setting('additional_stop_tax'),  ['class' => 'form-control','placeholder'=>  trans("Additional Stop Price"),'min' => 0,'step' => 0.01]) !!}
                                        <div class="form-text text-muted">
                                            {{ __("If the order has more than one delivery place, for each additional place will be sum this price") }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('allow_custom_order_values', __('Allow courier customize order price'), [
                                        'class' => 'col-4 control-label text-right',
                                    ]) !!}
                                    <div class="checkbox icheck col">
                                        {!! Form::hidden('allow_custom_order_values', 0) !!}
                                        {!! Form::checkbox('allow_custom_order_values', 1, setting('allow_custom_order_values', false)) !!}
                                        <span
                                            class="ml-2">{{ __("If it's enabled, each courier will be allowed to set your custom base values in the app settings to use in order calculation when selected.") }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('app_tax', __("App Tax")." (%)", ['class' => 'col-4 control-label text-right']) !!}
                                    <div class="col-8">
                                        {!! Form::number('app_tax', setting('app_tax',0),  ['class' => 'form-control','placeholder'=>  __('Percentage that will go to the app on each order'),'min' => 0,'max' => 100,'step' => 0.01]) !!}
                                        <div class="form-text text-muted">
                                            {{ __("Percentage that will go to the app on each order") }}
                                        </div>
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

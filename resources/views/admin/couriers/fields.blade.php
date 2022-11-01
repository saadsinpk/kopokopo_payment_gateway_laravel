@push('page_css')
    <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
@endpush
<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User:') !!}
    {{$courier->user->name }} <a href="{{route('admin.users.edit', $courier->user->id) }}" class="btn btn-sm btn-primary">{{__('Edit User')}}</a>
</div>

<!-- Active Field -->
<div class="form-group col-sm-6" style="padding-top: 37px">
    <div class="checkbox icheck">
        <label class="w-100 ml-2 form-check-inline">
            {!! Form::hidden('active', 0) !!}
            {!! Form::checkbox('active', 1, $courier->active) !!}
            <span class="ml-2">{{__('Courier Active')}}</span>
        </label>
    </div>
</div>
<!-- Using App Pricing Field -->
<div class="form-group col-sm-6" style="padding-top: 37px">
    <div class="checkbox icheck">
        <label class="w-100 ml-2 form-check-inline">
            {!! Form::hidden('using_app_pricing', 0) !!}
            {!! Form::checkbox('using_app_pricing', 1, $courier->using_app_pricing) !!}
            <span class="ml-2">{{__('Using App Pricing')}}</span>
        </label>
    </div>
</div>

<!-- Base Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('base_price', __('Base Price').':') !!}
    {!! Form::number('base_price', null, ['class' => 'form-control','step' => 0.01,'min' =>0]) !!}
    <span class="help-block text-muted">{{__('Only used if is not using app pricing')}}</span>
</div>

<!-- Base Distance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('base_distance', __('Base Distance').':') !!}
    {!! Form::number('base_distance', null, ['class' => 'form-control','step' => 0.01,'min' =>0]) !!}
    <span class="help-block text-muted">{{__('Only used if is not using app pricing')}}</span>
</div>

<!-- Additional Distance Pricing Field -->
<div class="form-group col-sm-6">
    {!! Form::label('additional_distance_pricing', __('Additional Distance Pricing').':') !!}
    {!! Form::number('additional_distance_pricing', null, ['class' => 'form-control','step' => 0.01,'min' =>0]) !!}
    <span class="help-block text-muted">{{__('Only used if is not using app pricing')}}</span>
</div>

<!-- Return Distance Pricing Field -->
<div class="form-group col-sm-6">
    {!! Form::label('return_distance_pricing', __('Return Distance Pricing').':') !!}
    {!! Form::number('return_distance_pricing', null, ['class' => 'form-control','step' => 0.01,'min' =>0]) !!}
    <span class="help-block text-muted">{{__('Only used if is not using app pricing')}}</span>
</div>

<!-- Additional Stop Tax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('additional_stop_tax', __('Additional Stop Tax').':') !!}
    {!! Form::number('additional_stop_tax', null, ['class' => 'form-control','step' => 0.01,'min' =>0]) !!}
    <span class="help-block text-muted">{{__('Only used if is not using app pricing')}}</span>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.couriers.index') }}" class="btn btn-light">{{__('crud.cancel')}}</a>
</div>
@push('scripts')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
@endpush

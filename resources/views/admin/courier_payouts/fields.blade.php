<!-- Courier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('courier_id', __('Driver').':') !!}
    {!! Form::select('courier_id',$couriers, null, ['class' => 'form-control select2']) !!}
</div>

<!-- Method Field -->
<div class="form-group col-sm-6">
    {!! Form::label('method', __('Payment Method').':') !!}
    {!! Form::text('method', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', __('Amount').':') !!}
    {!! Form::number('amount', null, ['class' => 'form-control','min' => 0,'step' => 0.01]) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', __('Date').':') !!}
    {!! Form::date('date', null, ['class' => 'form-control','id'=>'date']) !!}
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.courierPayouts.index') }}" class="btn btn-light">{{__('crud.cancel')}}</a>
</div>

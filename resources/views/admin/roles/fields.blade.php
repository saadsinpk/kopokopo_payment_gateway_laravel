<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255]) !!}
</div>

<!-- Default Field -->
<div class="form-group col-sm-6" style="padding-top: 37px">
    <div class="form-check">
        {!! Form::hidden('default', '0') !!}
        {!! Form::checkbox('default', '1', null, ['id' => 'default']) !!}
        <label class="form-check-label" for="default">
           default
        </label>
    </div>
</div>

<!-- Guard Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('guard_name', 'Guard Name:') !!}
    {!! Form::text('guard_name', null, ['class' => 'form-control','maxlength' => 255]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.roles.index') }}" class="btn btn-light">{{__('crud.cancel')}}</a>
</div>

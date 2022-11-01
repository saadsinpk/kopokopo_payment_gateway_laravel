<!-- Courier Id Field -->
<div class="form-group">
    {!! Form::label('courier_id', 'Courier Id:') !!}
    <p>{{ $courierPayout->courier_id }}</p>
</div>

<!-- Method Field -->
<div class="form-group">
    {!! Form::label('method', 'Method:') !!}
    <p>{{ $courierPayout->method }}</p>
</div>

<!-- Amount Field -->
<div class="form-group">
    {!! Form::label('amount', 'Amount:') !!}
    <p>{{ $courierPayout->amount }}</p>
</div>

<!-- Date Field -->
<div class="form-group">
    {!! Form::label('date', 'Date:') !!}
    <p>{{ $courierPayout->date }}</p>
</div>


{!! Form::open(['route' => ['admin.courierPayouts.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('admin.courierPayouts.edit', $id) }}" class='btn btn-warning btn-sm'>
        <i class="fas fa-edit"></i>
    </a>
    {!! Form::button('<i class="fas fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-sm',
        'onclick' => "return confirm('Are you sure?')"
    ]) !!}
</div>
{!! Form::close() !!}

{!! Form::open(['route' => ['admin.roles.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @if(!in_array($id,[1,2,3]))
    <a href="{{ route('admin.roles.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fas fa-edit"></i>
    </a>
    {!! Form::button('<i class="fas fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Are you sure?')"
    ]) !!}
    @else
        <i class="fas fa-lock"></i>
    @endif
</div>
{!! Form::close() !!}

<div class='btn-group btn-group-sm'>
    <?php //@can('permissions.destroy') ?>
        {!! Form::open(['route' => ['admin.permissions.destroy', $id], 'method' => 'delete']) !!}
            {!! Form::button('<i class="fa fa-trash"></i>', [
                'type' => 'submit',
                'class' => 'btn btn-danger btn-xs',
                'onclick' => "return confirm('Are you sure?')"
            ]) !!}
        {!! Form::close() !!}
    <?php //@endcan ?>
</div>

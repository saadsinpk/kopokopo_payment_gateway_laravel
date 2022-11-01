<!-- Picture Field -->
<div class="form-group col-sm-6">
    {!! Form::label('picture', __("Picture").':', ['class' => 'control-label']) !!}
    <div class="input-logo">
        <input type="file" name="image" class="form-control" id="inputPicture" style="" accept="image/*" onchange="loadFile(event)">
        <div class="form-text text-muted">
            @if(isset($user))
                {{__('Leave empty to not change')}}
            @endif
        </div>
    </div>
    <div class="text-center">
        <img id="imagePreview" src="{{((isset($user) && $user->hasMedia('default'))?$user->getFirstMediaUrl('default', 'icon'):asset('img/avatardefault.png'))}}" alt="Image Preview" style="max-width: 50%" />
    </div>
</div>
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
                output.src = "{{((isset($user) && $user->hasMedia('default'))?$user->getFirstMediaUrl('default', 'icon'):asset('img/avatardefault.png'))}}";
                output.style.display = 'block';
            }
        };

    </script>

@endpush
<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('roles[]', __('Roles').':') !!}
    {!! Form::select('roles[]',$roles, null, ['class' => 'form-control select2','multiple' => 'multiple']) !!}
</div>
<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255]) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control','maxlength' => 255]) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control','maxlength' => 255]) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control','maxlength' => 255]) !!}
    <div class="form-text text-muted">
        @if(isset($user))
            {{__('Leave empty to not change')}}
        @endif
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.users.index') }}" class="btn btn-light">{{__('crud.cancel')}}</a>
</div>

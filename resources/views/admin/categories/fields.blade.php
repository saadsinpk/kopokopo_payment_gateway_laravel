<!-- Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image', __('Image Icon').':') !!}
    <input type="file" name="image" class="form-control" id="inputImage" style="" accept="image/*" onchange="loadFile(event)">
    <div class="col-12 pt-2 text-center">
        <center>
            <img id="imagePreview" src="{{((isset($category) && $category->getHasMediaAttribute())?$category->getFirstMediaUrl('default'):asset('/img/image_default.png'))}}" alt="Image Preview" onchange="loadFile(event)" style="display: {{(isset($category) && $category->getHasMediaAttribute())?'inline-block':'none'}};max-width: 90%" />
        </center>
    </div>
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.categories.index') }}" class="btn btn-light">{{__('crud.cancel')}}</a>
</div>
@push('scripts')
    <script type="text/javascript">
        var outputImage = document.getElementById('imagePreview');

        var loadFile = function(event) {

            if(event.target.files.length > 0) {
                outputImage.src = URL.createObjectURL(event.target.files[0]);
                outputImage.onload = function () {
                    URL.revokeObjectURL(outputImage.src) // free memory
                }
                outputImage.style.display = 'block';
            }else{
                @if(isset($category))
                    outputImage.src = "{{$category->getFirstMediaUrl('default')}}";
                outputImage.style.display = 'block';
                @endif

            }
        };
    </script>
@endpush

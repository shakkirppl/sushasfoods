@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="col-12 grid-margin createtable">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">Edit What In Farm</h4>
                </div>
                <div class="col-md-6 heading text-end">
                    <a href="{{ route('what-in-farm.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="form-sample mt-3" action="{{ route('what-in-farm.update', $farm->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group row">
    <label class="col-sm-2 col-form-label required">Category</label>
    <div class="col-sm-9">
        <select class="form-control form-control-lg" id="category" name="category" required>
            <option value="main" {{ $farm->category === 'main' ? 'selected' : '' }}>Main</option>
            <option value="Fruits" {{ $farm->category === 'Fruits' ? 'selected' : '' }}>Fruits</option>
            <option value="Vegitable" {{ $farm->category === 'Vegitable' ? 'selected' : '' }}>Vegitable</option>
       
                       <option value="Tubers"  {{ $farm->category === 'Tubers' ? 'selected' : '' }}>Tubers</option> 
                    
                       <option value="Herbs"  {{ $farm->category === 'Herbs' ? 'selected' : '' }}>Herbs</option>
                     <option value="Spices"  {{ $farm->category === 'Spices' ? 'selected' : '' }}>Spices</option>
                      <option value="Animal care and maintenance"  {{ $farm->category === 'Animal care and maintenance' ? 'selected' : '' }}>Animal care and maintenance </option>
                      <option value="Ornamental Plants"  {{ $farm->category === 'Ornamental Plants' ? 'selected' : '' }}>Ornamental  Plants </option>
                      <option value="Garden Plants"  {{ $farm->category === 'Garden Plants' ? 'selected' : '' }}>Garden Plants</option>
                      <option value="Aquatic plants"  {{ $farm->category === 'Aquatic plants' ? 'selected' : '' }}>Aquatic plants</option>
        </select>
    </div>
</div>

                <!-- Name -->
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label required">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" value="{{ old('name', $farm->name) }}" required/>
                    </div>
                </div>

                <!-- Image -->
                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" name="image" accept="image/*"/>
                        @if($farm->image)
                            <div class="mt-2">
                                <img src="{{ asset('uploads/what_in_farm/'.$farm->image) }}" width="120" alt="Current Image">
                                <p class="mt-1"><small>Current image</small></p>
                            </div>
                        @endif
                    </div>
                </div>
@if ($farm->images->count())
    <div class="form-group row mt-2">
        <label class="col-sm-2 col-form-label">Existing Gallery Images</label>
        <div class="col-sm-9 d-flex flex-wrap gap-2">
            @foreach ($farm->images as $image)
        <div style="position: relative; display: inline-block; margin-right: 10px;" class="image-wrapper">
    <img src="{{ asset('uploads/what_in_farm/gallery/' . $image->image) }}" width="100" style="border: 1px solid #ccc; padding: 5px;">
    <button type="button" 
            class="btn btn-danger btn-sm remove-image" 
            data-id="{{ $image->id }}" 
            style="position: absolute; top: 0; right: 0;">X</button>
</div>
            @endforeach
        </div>
    </div>
@endif
    <!-- Upload New Images -->
    <input type="file" name="images[]" id="image-upload" multiple>
    <div id="preview"></div>
                <!-- Description -->
                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description" rows="4">{{ old('description', $farm->description) }}</textarea>
                    </div>
                </div>

                  <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 1</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description1">{{ old('description1', $farm->description1) }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 2</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description2">{{ old('description2', $farm->description2) }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 3</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description3">{{ old('description3', $farm->description3) }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 4</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description4">{{ old('description4', $farm->description4) }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 5</label>
                    <div class="col-sm-9">
                         <textarea class="form-control" name="description5" id="description-editor">{{ old('description5', $farm->description5) }}</textarea>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="submitbutton mt-3">
                    <button type="submit" class="btn btn-primary mb-2 submit">Update <i class="fas fa-save"></i></button>
                </div>
            </form>

        </div>
    </div>
</div>
</div>
</div>

@endsection
@section('script')
<!-- jQuery (must be before Summernote JS) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script>
    $(document).ready(function () {
        $('#description-editor').summernote({
            height: 200,
            placeholder: 'Enter description here...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
<script>
    $('#image-upload').on('change', function () {
        $('#preview').empty(); // clear previous preview
        const files = this.files;

        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').append(
                    `<img src="${e.target.result}" style="width: 100px; height: auto; margin-right:10px;">`
                );
            }
            reader.readAsDataURL(files[i]);
        }
    });
</script>
<script>
$(document).on('click', '.remove-image', function () {
    const imageId = $(this).data('id');
    const wrapper = $(this).closest('.image-wrapper');

    if (confirm('Are you sure you want to delete this image?')) {
        $.ajax({
            url: "{{ url('what-in-farm/image') }}/" + imageId,
            type: 'POST',   // Laravel needs POST + _method for DELETE
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE' // 👈 Spoofs DELETE
            },
            success: function () {
                wrapper.remove();
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    }
});
</script>

@endsection
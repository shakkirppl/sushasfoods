@extends('layouts.layout')
@section('content')
  <!-- Summernote CSS -->

<div class="main-panel">
<div class="content-wrapper">
<div class="col-12 grid-margin createtable">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">Create What In Farm</h4>
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

            <form class="form-sample mt-3" action="{{ route('what-in-farm.store') }}" method="post" enctype="multipart/form-data">
                @csrf


      

                 <div class="form-group row">
                    <label class="col-sm-2 col-form-label required">Category</label>
                    <div class="col-sm-9">
                       <select class="form-control form-control-lg" id="category" name="category" required>
                      <option value="main">Main</option>
                       <option value="Tubers">Tubers</option> 
                       <option value="Fruits">Fruits</option>
                       <option value="Vegetables">Vegetables</option> 
                       <option value="Herbs">Herbs</option>
                     <option value="Spices">Spices</option>
                      <option value="Animal care and maintenance">Animal care and maintenance </option>
                      <option value="Ornamental Plants">Ornamental  Plants </option>
                      <option value="Garden Plants">Garden Plants</option>
                      <option value="Aquatic plants">Aquatic plants</option>
                    
                       </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label required">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required/>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" name="image" accept="image/*"/>
                    </div>
                </div>
<div class="form-group row mt-2">
    <label class="col-sm-2 col-form-label">Aditional Images</label>
    <div class="col-sm-9">
        <input type="file" class="form-control" name="gallery_images[]" multiple accept="image/*">
    </div>
</div>
                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 1</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description1">{{ old('description1') }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 2</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description2">{{ old('description2') }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 3</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description3">{{ old('description3') }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 4</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description4">{{ old('description4') }}</textarea>
                    </div>
                </div>

                       <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description 5</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" name="description5" id="description-editor">{{ old('description5') }}</textarea>
                    </div>
                </div>

                <div class="submitbutton mt-3">
                    <button type="submit" class="btn btn-primary mb-2 submit">Submit <i class="fas fa-save"></i></button>
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
    document.querySelector('input[name="gallery_images[]"]').addEventListener('change', function (e) {
        const container = document.getElementById('preview-container');
        container.innerHTML = ''; // Clear previous previews

        Array.from(e.target.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.marginRight = '10px';
                    img.style.border = '1px solid #ccc';
                    img.style.padding = '5px';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

@endsection
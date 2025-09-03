@extends('layouts.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
<div class="col-12 grid-margin createtable">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title">Create Category</h4>
                </div>
                <div class="col-md-6 heading text-end">
                    <a href="{{ route('categories.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                </div>
            </div>

            <div class="mt-3">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form class="form-sample" action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label required">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required/>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Tagline</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="tagline" value="{{ old('tagline') }}"/>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" name="image" accept="image/*"/>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label class="col-sm-2 col-form-label">Terms</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="terms">{{ old('terms') }}</textarea>
                    </div>
                </div>

                <div class="submitbutton mt-3">
                    <button type="submit" class="btn btn-primary mb-2 submit">Submit<i class="fas fa-save"></i></button>
                </div>
            </form>

        </div>
    </div>
</div>
</div>
</div>

@endsection

@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Categories</h4>
                        </div>
                        <div class="col-6 text-end heading">
                            <a href="{{ route('categories.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                        </div>
                    </div>

                    @if($message = Session::get('success'))
                        <div class="alert alert-success mt-2">{{ $message }}</div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table table-hover" id="value-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Tagline</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $key => $category)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if($category->image)
                                                <img src="{{ asset('uploads/categories/'.$category->image) }}" width="80">
                                            @endif
                                        </td>
                                        <td>{{ $category->tagline }}</td>
                                        <td>
                                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="post" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5">No categories found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#value-table').DataTable();
    });
</script>
@endsection

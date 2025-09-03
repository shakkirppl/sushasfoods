@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">What Do In Farm</h4>
                        </div>
                        <div class="col-6 text-end heading">
                            <a href="{{ route('what-in-farm.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
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
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($farms as $key => $farm)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $farm->name }}</td>
                                        <td>
                                            @if($farm->image)
                                                <img src="{{ asset('uploads/what_in_farm/'.$farm->image) }}" width="80">
                                            @endif
                                        </td>
                                        <td>{{ $farm->description }}</td>
                                        <td>
                                            <a href="{{ route('what-in-farm.edit', $farm->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('what-in-farm.destroy', $farm->id) }}" method="post" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5">No records found.</td></tr>
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

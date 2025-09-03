@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Video</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ route('home-video-gallary.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                        </div>
                    </div>

                    @if($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover" id="value-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Video Preview</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($videoGallary))
                                    @foreach($videoGallary as $key => $gallary)
                                        <tr id="{{ $gallary->id }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <video width="200" controls>
                                                    <source src="{{ asset('uploads/videos/'.$gallary->video) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <br>
                                                <small>{{ $gallary->video }}</small>
                                            </td>
                                            <td>
                                                <form action="{{ route('home-video-gallary.destroy', $gallary->id) }}" method="post" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">Sorry, no records found!</td>
                                    </tr>
                                @endif
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

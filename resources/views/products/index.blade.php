@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
  <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    
                     <div class="row">
                    <div class="col-6 col-md-6 col-sm-6 col-xs-12" >
                             <h4 class="card-title">Products</h4>
                    </div>
                    <div class="col-6 col-md-6 col-sm-6 col-xs-12  heading" style="text-align:end;">
                    <a href="{{ route('products.create') }}"  class="newicon large-button"><i class="mdi mdi-new-box"></i></a>
                    </div>
                       
                   
                </div>
                    
@if($message = Session::get('success'))
<div class="alert alert-sucess">
  <p>{{$message}}</p>
</div>
@endif
 
                 
                  <p class="card-description">
                
                  </p>
                  <div class="table-responsive">
              
                    <table class="table table-hover"id="value-table">
        <thead>
            <tr>
              <th></th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
              <th><img loading="lazy" src="{{ url('uploads/products/' . $product->image) }}" width="100px" height="100px" class="product-image"
              alt="Hair Care Oil Product Image" /></th>
                <td>{{ $product->product_name }}</td>
              
                <td>
                    <a href="{{ url('products.addon', $product->id) }}" class="btn btn-primary btn-sm">Addon Quantity</a>
                    <a href="{{ url('products.show', $product->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ url('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                </td>
            </tr>
            @endforeach
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
    $(document).ready( function () {
    $('#value-table').DataTable();
} );
</script>
@endsection

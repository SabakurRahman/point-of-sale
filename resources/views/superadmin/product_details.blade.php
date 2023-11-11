

@extends('layout')

@section('content')
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">All Product</h4>

                       

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Product List</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <a href="{{ route('add_product') }}">
                                        <span class="text-muted btn btn-success">Add Product</span>
                                    </a>
                                </ol>
                            </div>
                        </div>
                        <div class="card-body">  
                            <div class="table-responsive">
                                <table class="table mb-0"> <!-- table mb-0-->

                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>description</th>
                                            <th>Category</th>
                                            <th>Store</th>
                                            <th>Admin</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                        
                                        <tr>
                                            <th scope="row">{{ $product->id }}</th>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->price }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $product->description }}</td>
                                            <td>{{ $product->category_id }}</td>
                                            <td>{{ $product->store_id }}</td>
                                            <td>{{ $product->creator_id }}</td>
                                            <td>{{ $product->status }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" value="{{ $product->id }}" href="#">Details</a>
                                                        <a class="dropdown-item" value="{{ $product->id }}" href="#">Edit</a>
                                                        <a class="dropdown-item" value="{{ $product->id }}" href="#">All Store</a>
                                                        <a class="dropdown-item" value="{{ $product->id }}" href="#">All Product</a>
                                                    </div>
                                                </div>
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
@endsection
        <!-- END layout-wrapper -->

     
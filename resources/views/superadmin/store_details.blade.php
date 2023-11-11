
@extends('layout')

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">All Store</h4>

                      

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                       
                        <div class="card-header page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Store List</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <a href="{{ route('addstore.page') }}">
                                        <span class="text-muted btn btn-success">Add Store</span>
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
                                            <th>Store Name</th>
                                            <th>Address</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Admin</th>
                                            <th>product</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stores as $store)
                                        
                                        <tr>
                                            <th scope="row">{{ $store->id }}</th>
                                            <td>{{ $store->name }}</td>
                                            <td>{{ $store->address }}</td>
                                            <td>{{ $store->description }}</td>
                                            <td>{{ $store->status }}</td>
                                            <td>{{ $store->name }}</td>
                                            <td>{{ $store->name }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" value="{{ $store->id }}" href="#">Details</a>
                                                        <a class="dropdown-item" value="{{ $store->id }}" href="#">Edit</a>
                                                        <a class="dropdown-item" value="{{ $store->id }}" href="#">All Store</a>
                                                        <a class="dropdown-item" value="{{ $store->id }}" href="#">All Product</a>
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
<!-- END layout-wrapper -->
@endsection

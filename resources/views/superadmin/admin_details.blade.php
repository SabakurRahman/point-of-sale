
@extends('layout')

@section('content')


<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">All Admin</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                         
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Admin List</h4>
                            
                        </div>
                        <div class="card-body">  
                            <div class="table-responsive">
                                <table class="table mb-0"> <!-- table mb-0-->

                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                        
                                        <tr>
                                            <th scope="row">{{ $user->id }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" value="{{ $user->id }}" href=" {{ route('detail_admin', ['id' => $user->id] ) }}">Details</a>
                                                       
                                                       

                                                        <a class="dropdown-item" value="{{ $user->id }}" href="">Edit</a>
                                                        <a class="dropdown-item" value="{{ $user->id }}" href="#">All Store</a>
                                                        <a class="dropdown-item" value="{{ $user->id }}" href="#">All Product</a>
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
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            
        <!-- END layout-wrapper -->

       
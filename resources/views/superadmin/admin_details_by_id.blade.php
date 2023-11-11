
@extends('layout')

@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Admin Details</h4>

                       

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                       
                        <div class="card-body">  
                            <div class="table-responsive">
                                <table class="table mb-0"> <!-- table mb-0-->

                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Total Store</th>
                                            <th>Total Product</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $users->id }}</td>
                                            <td>{{ $users->name }}</td>
                                            <td>{{ $users->email }}</td>
                                            <td>{{ $users->address }}</td>
                                            <td>{{ $stores->count() }}</td>
                                            <td>{{ $products->count() }}</td>
                                            <td>{{ $users->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                   <a class="btn btn-sm btn-primary m-2" href="{{ route('admin_edit.page', ['user_id' => $users->id]) }}">Edit</a>
                                                   <a class="btn btn-sm btn-primary m-2" href="{{ route('addstore.page', ['user_id' => $users->id]) }}">Add Store</a>
                                                   <a class="btn btn-sm btn-primary m-2" href="{{ route('add_product', ['user_id' => $users->id]) }}">Add Product</a>
                                                   
                                                   <a class="btn btn-sm btn-primary m-2" href="{{ route('delete.admin', ['user_id' => $users->id]) }}">Delete</a>
                                                   
                                                   
                                            </td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="align-items-center d-flex">
                                <div class="card-title mb-0 flex-grow-1">
                                    <h4 class="card-title">Employee</h4>
                                </div>
                                <div class="flex-shrink-0">
                               
                                        <div class="dropdown btn btn-success">
                                            <a href="{{ route('addstore.page', ['user_id' => $users->id]) }}">
                                                <span class="text-muted">Add Employee</span>
                                            </a>
                                       
                
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                            {{-- <a class="dropdown-item" href="#">Members</a>
                                            <a class="dropdown-item" href="#">Old Members</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                        <div class="card-body">  
                            <div class="table-responsive">
                                <table class="table -0"> <!-- table mb-0-->

                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($employee->isEmpty())
                                        <tr>
                                            <td colspan="3" class="text-primary text-center">No Employee available</td>
                                        </tr>
                                    @else
                                        @foreach ($employee as $employee)
                                        <tr>
                                            <td>{{ $employee->id }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->phone }}</td>
                                            <td>{{ $employee->created_at->format('Y-m-d') }}</td>
                                           
                                            <td>
                                                <div >
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('adminstore.edit', ['user_id' => $users->id, 'store_id' => $store->id]) }}">
                                                        Edit
                                                    </a>
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('adminstore.delete', ['store_id' => $store->id]) }}">
                                                        Delete
                                                    </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                       @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="align-items-center d-flex">
                                <div class="card-title mb-0 flex-grow-1">
                                    <h4 class="card-title">All Store</h4>
                                </div>
                                <div class="flex-shrink-0">
                               
                                        <div class="dropdown btn btn-success">
                                            <a href="{{ route('addstore.page', ['user_id' => $users->id]) }}">
                                                <span class="text-muted">Add Store</span>
                                            </a>
                                       
                
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                            {{-- <a class="dropdown-item" href="#">Members</a>
                                            <a class="dropdown-item" href="#">Old Members</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                        <div class="card-body">  
                            <div class="table-responsive">
                                <table class="table -0"> <!-- table mb-0-->

                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($stores->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-primary text-center">No Stores available</td>
                                        </tr>
                                    @else
                                        @foreach ($stores as $store)
                                        <tr>
                                            <td>{{ $store->id }}</td>
                                            <td>{{ $store->name }}</td>
                                            <td>{{ $store->address }}</td>
                                            <td>{{ $store->description }}</td>
                                            <td>{{ $store->created_at->format('Y-m-d') }}</td>
                                           
                                            <td>
                                                <div >
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('addcategorypage', ['user_id' => $users->id, 'store_id' => $store->id]) }}">
                                                        Add Category
                                                    </a>
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('add_product', ['user_id' => $users->id, 'store_id' => $store->id]) }}">
                                                        Add Product
                                                    </a>
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('adminstore.edit', ['user_id' => $users->id, 'store_id' => $store->id]) }}">
                                                        Edit
                                                    </a>
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('adminstore.delete', ['store_id' => $store->id]) }}">
                                                        Delete
                                                    </a>
                                                   
                                            </td>
                                        </tr>
                                        @endforeach
                                       @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="align-items-center d-flex">
                                <div class="card-title mb-0 flex-grow-1">
                                    <h4 class="card-title">All Categories</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="dropdown btn btn-success">
                                        <a href="{{ route('addcategorypage',$users->id) }}">
                                            <span class="text-muted">Add Category</span>
                                        </a>
                                       
                
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                            {{-- <a class="dropdown-item" href="#">Members</a>
                                            <a class="dropdown-item" href="#">Old Members</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">  
                            <div class="table-responsive">
                                <table class="table mb-0"> <!-- table mb-0-->
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Store</th>
                                            <th>date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($categories->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-primary text-center">No categories available</td>
                                        </tr>
                                    @else
                                        @foreach ($categories as $categorie)
                                            <tr>
                                                <td>{{ $categorie->id }}</td>
                                                <td>{{ $categorie->name }}</td>
                                                <td>{{ $categorie->image }}</td>
                                                <td>{{ $categorie->store_id }}</td>
                                                <td>{{ $categorie->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('add_product', ['user_id' => $users->id, 'store_id' => $store->id,'category_id' => $categorie->id]) }}">
                                                        Add Product
                                                    </a>
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('edit.catgory', ['cat_id' => $categorie->id]) }}">
                                                        Edit
                                                    </a>
                                                    <a class="btn btn-sm btn-primary m-2" href="{{ route('deleteCategory', ['category_id' => $categorie->id]) }}">
                                                        Delete
                                                    </a>
                                                   
                                             </td>
                                              
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="card">
                       
                        <div class="card-header">
                            <div class="align-items-center d-flex">
                                <div class="card-title mb-0 flex-grow-1">
                                    <h4 class="card-title">All Product</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="dropdown btn btn-success">
                                        <a href="{{ route('add_product', ['user_id' => $users->id]) }}">
                                            <span class="text-muted">Add Product</span>
                                        </a>
                                       
                
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                            {{-- <a class="dropdown-item" href="#">Members</a>
                                            <a class="dropdown-item" href="#">Old Members</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">  
                            <div class="table-responsive">
                                <table class="table -0"> <!-- table mb-0-->

                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Store</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($products->isEmpty())
                                        <tr>
                                            <td colspan="11" class="text-primary text-center">No Stores available</td>
                                        </tr>
                                    @else
                                        @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->image }}</td>
                                            <td>{{ $product->price }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $product->description }}</td>
                                            <td>{{ $product->category_id }}</td>
                                            <td>{{ $product->store_id }}</td>
                                            <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                           
                                            <td>
                                                  
                                                <a class="btn btn-sm btn-primary m-2" href="{{ route('edit.product', ['product_id' => $product->id]) }}">Edit</a>      
                                                <a class="btn btn-sm btn-primary m-2" href="{{ route('product.delete', ['product_id' => $product->id]) }}">Delete</a>      
                                                
                                            </td>
                                        </tr>
                                        
                                        @endforeach
                                       @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                
              
            </div>
            <!-- end row -->

          
           
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Responsive table</h4>
                            <p class="card-title-desc">
                                Create responsive tables by wrapping any <code>.table</code> in <code>.table-responsive</code>
                                to make them scroll horizontally on small devices (under 768px).
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Table heading</th>
                                            <th>Table heading</th>
                                            <th>Table heading</th>
                                            <th>Table heading</th>
                                            <th>Table heading</th>
                                            <th>Table heading</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                            <td>Table cell</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> &copy; Symox.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Themesbrand.com/" target="_blank" class="text-reset">Themesbrand</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div> 



@endsection

@extends('layout')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

@section('content')

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        <form method="POST" action="{{ route('product_store_post') }}">
                            @csrf
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Add Product For Admin</h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-body">
                                        <div class="mb-3 row">
                                            <label class="col-md-2 col-form-label">Admin Name</label>
                                            <div class="col-md-10">
                                                <select name="creator_id" class="form-select">
                                                    <option>Select</option>
                                                    @if ($user_id)
                                                    <option value="{{ $user_id }}" selected>{{ $users->name }}</option>
                                                @else
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                @endif
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-md-2 col-form-label">Store Name</label>
                                            <div class="col-md-10">
                                                <select name="store_id" class="form-select">
                                                    <option>Select</option>
                                                    @if ($store_id)
                                                    <option value="{{ $store_id }}" selected>{{ $store->name }}</option>
                                                @else
                                                    @foreach ($store as $store)
                                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                                    @endforeach
                                                @endif
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-md-2 col-form-label">Category Name</label>
                                            <div class="col-md-10">
                                                <select name="category_id" class="form-select">
                                                    <option>Select</option>
                                                    @if ($category_id)
                                                    <option value="{{ $category_id }}" selected>{{ $category->name }}</option>
                                                @else
                                                    @foreach ($category as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                                    
                                                </select>
                                            </div>
                                        </div>
                                      
                                        
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Name</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Buying Price</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="buying_price" name="buying_price" placeholder="Enter Buying Price">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Price</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Quantity</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Description</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="expiry_date" class="col-md-2 col-form-label">Expiry Date</label>
                                            <div class="col-md-10">
                                                <input type="text" class="" id="expiry_date" name="expiry_date" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" required>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Image</label>
                                            <div class="col-md-10">
                                               
                                                <input type="file" class="form-control" id="image" name="image"  aria-label="Upload">

                                            </div>
                                        </div>
                                       

                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Register</button>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </form>
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
            <!-- end main content-->
            @endsection

            <script>

            </script>
@extends('layout')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

@section('content')

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        <form method="POST" action="{{ route('edit.product_post') }}">
                            @csrf
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">Update Product </h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-body">
                                       
                                        
                                        
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Name</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" value="{{ $product->name }}" id="name" name="name" placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Buying Price</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" value="{{ $product->buying_price }}" id="buying_price" name="buying_price" placeholder="Enter Buying Price">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Price</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" value="{{ $product->price }}" id="price" name="price" placeholder="Enter Price">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Quantity</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" value="{{ $product->quantity }}" id="quantity" name="quantity" placeholder="Enter Quantity">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="example-text-input" class="col-md-2 col-form-label">Description</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" value="{{ $product->description }}" id="description" name="description" placeholder="Enter Description">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="expiry_date" class="col-md-2 col-form-label">Expiry Date</label>
                                            <div class="col-md-10">
                                                <input type="text" class="" id="expiry_date" value="{{ $product->expiry_date }}" name="expiry_date" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" required>
                                            </div>
                                        </div>
                                      
                                        <input type="hidden" value="{{ $product->id }}" name="product_id" >


                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Update</button>
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
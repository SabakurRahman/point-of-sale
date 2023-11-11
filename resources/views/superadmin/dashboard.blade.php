


@extends('layout')

@section('content')

@if (auth()->check() && auth()->user()->type === 'superadmin')
    @php
        $redirectUrl = route('login');
        $message = 'Please log in to continue.';
        return redirect($redirectUrl)->with('success', $message);
    @endphp
@endif

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                   
                                    <h4 class="mb-0">Welcome !</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Symox</a></li>
                                            <li class="breadcrumb-item active">Welcome !</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card bg-primary">
                                    <div class="card-body">
                                        <div class="text-center py-3">
                                            <ul class="bg-bubbles ps-0">
                                                <li><i class="bx bx-grid-alt font-size-24"></i></li>
                                                <li><i class="bx bx-tachometer font-size-24"></i></li>
                                                <li><i class="bx bx-store font-size-24"></i></li>
                                                <li><i class="bx bx-cube font-size-24"></i></li>
                                                <li><i class="bx bx-cylinder font-size-24"></i></li>
                                                <li><i class="bx bx-command font-size-24"></i></li>
                                                <li><i class="bx bx-hourglass font-size-24"></i></li>
                                                <li><i class="bx bx-pie-chart-alt font-size-24"></i></li>
                                                <li><i class="bx bx-coffee font-size-24"></i></li>
                                                <li><i class="bx bx-polygon font-size-24"></i></li>
                                            </ul>
                                           <div class="main-wid position-relative">
                                                <h3 class="text-white">Symox Dashboard</h3>

                                                <h3 class="text-white mb-0"> Welcome Back, Peter Kelsey!</h3>

                                                <p class="text-white-50 px-4 mt-4">Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien libero tincidunt.</p>
                                                
                                                <div class="mt-4 pt-2 mb-2">
                                                    <a href="" class="btn btn-success">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="avatar">
                                                    <span class="avatar-title bg-primary-subtle rounded">
                                                        <i class="mdi mdi-shopping-outline text-primary font-size-24"></i>
                                                    </span>
                                                </div>
                                                <p class="text-muted mt-4 mb-0">Today Orders</p>
                                                <h4 class="mt-1 mb-0">3,89,658 <sup class="text-success fw-medium font-size-14"><i class="mdi mdi-arrow-down"></i> 10%</sup></h4>
                                                <div>
                                                    <div class="py-3 my-1">
                                                        <div id="mini-1" data-colors='["#3980c0"]'></div>
                                                    </div>
                                                    <ul class="list-inline d-flex justify-content-between justify-content-center mb-0">
                                                        <li class="list-inline-item"><a href="" class="text-muted">Day</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Week</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Month</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Year</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="avatar">
                                                    <span class="avatar-title bg-success-subtle rounded">
                                                        <i class="mdi mdi-eye-outline text-success font-size-24"></i>
                                                    </span>
                                                </div>
                                                <p class="text-muted mt-4 mb-0">Today Visitor</p>
                                                <h4 class="mt-1 mb-0">1,648,29 <sup class="text-danger fw-medium font-size-14"><i class="mdi mdi-arrow-down"></i> 19%</sup></h4>
                                                <div>
                                                    <div class="py-3 my-1">
                                                        <div id="mini-2" data-colors='["#33a186"]'></div>
                                                    </div>
                                                    <ul class="list-inline d-flex justify-content-between justify-content-center mb-0">
                                                        <li class="list-inline-item"><a href="" class="text-muted">Day</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Week</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Month</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Year</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="avatar">
                                                    <span class="avatar-title bg-primary-subtle rounded">
                                                        <i class="mdi mdi-rocket-outline text-primary font-size-24"></i>
                                                    </span>
                                                </div>
                                                <p class="text-muted mt-4 mb-0">Total Expense</p>
                                                <h4 class="mt-1 mb-0">6,48,249 <sup class="text-success fw-medium font-size-14"><i class="mdi mdi-arrow-down"></i> 22%</sup></h4>
                                                <div>
                                                    <div class="py-3 my-1">
                                                        <div id="mini-3" data-colors='["#3980c0"]'></div>
                                                    </div>
                                                    <ul class="list-inline d-flex justify-content-between justify-content-center mb-0">
                                                        <li class="list-inline-item"><a href="" class="text-muted">Day</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Week</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Month</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Year</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="avatar">
                                                    <span class="avatar-title bg-success-subtle rounded">
                                                        <i class="mdi mdi-account-multiple-outline text-success font-size-24"></i>
                                                    </span>
                                                </div>
                                                <p class="text-muted mt-4 mb-0">New Users</p>
                                                <h4 class="mt-1 mb-0">$5,265,3 <sup class="text-danger fw-medium font-size-14"><i class="mdi mdi-arrow-down"></i> 18%</sup></h4>
                                                <div>
                                                    <div class="py-3 my-1">
                                                        <div id="mini-4" data-colors='["#33a186"]'></div>
                                                    </div>
                                                    <ul class="list-inline d-flex justify-content-between justify-content-center mb-0">
                                                        <li class="list-inline-item"><a href="" class="text-muted">Day</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Week</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Month</a></li>
                                                        <li class="list-inline-item"><a href="" class="text-muted">Year</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap align-items-center mb-3">
                                            <h5 class="card-title mb-0">Sales Statistics</h5>
                                            <div class="ms-auto">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="text-muted font-size-12">Sort By:</span> <span class="fw-medium">Weekly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                       <div class="row align-items-center">
                                        <div class="col-xl-8">
                                            <div>
                                                 <div id="sales-statistics" data-colors='["#eff1f3","#eff1f3","#eff1f3","#eff1f3","#33a186","#3980c0","#eff1f3","#eff1f3","#eff1f3", "#eff1f3"]' class="apex-chart"></div>
                                            </div>
                                          </div>
                                           <div class="col-xl-4">
                                               <div class="">
                                                    <div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex">
                                                                <i class="mdi mdi-circle font-size-10 mt-1 text-primary"></i>
                                                                <div class="flex-1 ms-2">
                                                                    <p class="mb-0">Product Order</p>
                                                                    <h5 class="mt-1 mb-0 font-size-16">43,541.58</h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <span class="badge text-primary bg-primary-subtle">25.4%<i class="mdi mdi-arrow-down ms-2"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 border-top pt-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex">
                                                                <i class="mdi mdi-circle font-size-10 mt-1 text-primary"></i>
                                                                <div class="flex-1 ms-2">
                                                                    <p class="mb-0">Product Pending</p>
                                                                    <h5 class="mt-1 mb-0 font-size-16">17,351.12</h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <span class="badge text-primary bg-primary-subtle">17.4%<i class="mdi mdi-arrow-down ms-2"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 border-top pt-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex">
                                                                <i class="mdi mdi-circle font-size-10 mt-1 text-success"></i>
                                                                <div class="flex-1 ms-2">
                                                                    <p class="mb-0">Product Cancelled</p>
                                                                    <h5 class="mt-1 mb-0 font-size-16">32,569.74</h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <span class="badge text-success  bg-success-subtle">16.3%<i class="mdi mdi-arrow-up ms-1"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 border-top pt-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex">
                                                                <i class="mdi mdi-circle font-size-10 mt-1 text-primary"></i>
                                                                <div class="flex-1 ms-2">
                                                                    <p class="mb-0">Product Delivered</p>
                                                                    <h5 class="mt-1 mb-0 font-size-16">67,356.24</h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <span class="badge text-primary bg-primary-subtle">65.7%<i class="mdi mdi-arrow-up ms-1"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                               </div>
                                           </div>
                                           
                                       </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <h5 class="card-title mb-0">Sales By Category</h5>
                                            <div class="ms-auto">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="text-muted font-size-12">Sort By:</span> <span class="fw-medium">Weekly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <canvas class="mx-auto" id="sales-category" height="281" data-colors='["#3980c0","#51af98", "#4bafe1", "#B4B4B5", "#f1f3f4"]'></canvas>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col">
                                                <div class="px-2">
                                                    <div class="d-flex align-items-center mt-sm-0 mt-2">
                                                        <i class="mdi mdi-circle font-size-10 mt-1 text-primary"></i>
                                                        <div class="flex-grow-1 ms-2">
                                                            <p class="mb-0 text-truncate">Watch OS 8</p>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <span class="fw-bold">35.0%</span>
                                                        </div>
                                                    </div>
    
                                                    <div class="d-flex align-items-center mt-2">
                                                        <i class="mdi mdi-circle font-size-10 mt-1 text-success"></i>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="mb-0 text-truncate">Iphone 12</p>
                                                            </div>
                                                        <div class="flex-shrink-0">
                                                            <span class="fw-bold">15.0%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                               <div class="px-2">
                                                    <div class="d-flex align-items-center mt-sm-0 mt-2">
                                                            <i class="mdi mdi-circle font-size-10 mt-1 text-info"></i>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="mb-0 text-truncate">Horror Book</p>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <span class="fw-bold">8.0%</span>
                                                            </div>
                                                    </div>

                                                    <div class="d-flex align-items-center mt-2">
                                                            <i class="mdi mdi-circle font-size-10 mt-1 text-secondary"></i>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="mb-0 text-truncate">Smart 4k TV</p>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <span class="fw-bold">7.0%</span>
                                                            </div>
                                                    </div>
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                           <div class="col-xl-8">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="align-items-center d-flex">
                                                        <div class="card-title mb-0 flex-grow-1">
                                                            <a href="{{ route('admin.details') }}"> <h4  >All Users</h4></a>
                                                        </div>
                                                       
                                                        <div class="flex-shrink-0">
                                                            <div class="dropdown">
                                                            
                                                                <a href="{{ route('admin.register') }}">
                                                                    <span class="text-muted">Add</span>
                                                                </a>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body px-0 pt-2">
                                                    <div class="table-responsive px-1">
                                                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                                            <tbody>
                                                                @foreach ($users as $user)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="font-size-15 mb-1">{{ $user->name }}</h6>
                                                                        <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> {{ $user->address }}</p>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge text-primary  bg-success-subtle font-size-12">{{ $user->email }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="dropdown">
                                                                            <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                                <i class="mdi mdi-dots-vertical"></i>
                                                                            </a>
                                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                                <a class="dropdown-item" href="#">Action</a>
                                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                                                <div class="dropdown-divider"></div>
                                                                                <a class="dropdown-item" href="#">Separated link</a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                      
                                                    </div> <!-- end table-responsive -->
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-7">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="align-items-center d-flex">
                                                        <div class="card-title mb-0 flex-grow-1">
                                                            <a href="{{ route('store.details') }}">
                                                                <h4>All Store</h4>

                                                            </a>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="dropdown">
                                                                <a class=" dropdown-toggle" href="#" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <span class="text-muted">Sort<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                                </a>
                                                                <a href="{{ route('addstore.page') }}">
                                                                    <span class="text-muted">Add</span>
                                                                </a>
                                                               
                                        
                                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                                                    {{-- <a class="dropdown-item" href="#">Members</a>
                                                                    <a class="dropdown-item" href="#">Old Members</a> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-body px-0 pt-2">
                                                    <div class="table-responsive px-3" data-simplebar style="max-height: 393px;">
                                                        <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                                            <tbody>
                                                                
                                                        @foreach ($store as $store)
                                                                <tr>
                                                                   
                                                                    <td>
                                                                        <h6 class="font-size-15 mb-1">{{ $store->name }}</h6>
                                                                        <p class="text-muted font-size-13 mb-0"><i class="mdi mdi-map-marker"></i> {{ $store->address }}</p>
                                                                    </td>
                                                                   
                                                                    <td><span class="badge text-primary  bg-success-subtle font-size-12"> {{ $store->description }}</span></td>
                                                                    <td>
                                                                        <div class="dropdown">
                                                                            <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                                <i class="mdi mdi-dots-vertical"></i>
                                                                            </a>
                                    
                                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                                <a class="dropdown-item" href="#">Action</a>
                                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                                                <div class="dropdown-divider"></div>
                                                                                <a class="dropdown-item" href="#">Separated link</a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div> <!-- enbd table-responsive-->
                                                </div>
                                            </div>
                                        </div>
                               </div>
                           </div>
                           <div class="col-xl-4">

                               <div class="card">
                                   <div class="card-header">
                                        <div class="align-items-center d-flex">
                                            <div class="card-title mb-0 flex-grow-1">
                                                <a href="{{ route('product.details') }}">
                                                    <h4>All Product</h4>

                                                </a>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a href="{{ route('add_product') }}" >
                                                        <span class="text-muted">Add</span>
                                                    </a>
                            
                                                   
                                                </div>
                                            </div>
                                        </div>
                                   </div>
                                   <div class="card-body pt-1">

                                        <div class="table-responsive">
                                            <table class="table table-centered align-middle table-nowrap mb-0">
                                              
                                                <tbody>
                                                    @foreach ($products as $product)

                                                    <tr>
                                                        <td><img src="{{ asset('uploads/product/' . $product->image) }}" class="me-3" height="18">{{ $product->name }}</td>
                                                        <td>
                                                            {{ $product->price }}
                                                        </td>
                                                        <td>
                                                          <i class="bx bx-trending-up text-success"></i>
                                                        </td>
                                                        <td>
                                                            {{ $product->quantity }}
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-20" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>
                        
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
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

                               <div class="card best-product">
                                <div class="card-body">
                                 <div class="row align-items-center justify-content-start">
                                     <div class="col-lg-8">
                                         <h5 class="best-product-title">Best Selling Product</h5>
                                         <div class="row align-items-end mt-4">
                                             <div class="col-4">
                                                 <div class="mt-1">
                                                     <h4 class="font-size-20 best-product-title">2,562</h4>
                                                     <p class="mb-0">Sold</p>
                                                 </div>
                                             </div>
                                             <div class="col-4">
                                                 <div class="mt-1">
                                                     <h4 class="font-size-20 best-product-title">4,652</h4>
                                                     <p class="mb-0">Stock</p>
                                                 </div>
                                             </div>

                                             <div class="col-4">
                                                 <div class="mt-1">
                                                     <a href="" class="btn btn-primary btn-sm">Buy
                                                         Now</a>
                                                 </div>
                                             </div>
                                            </div>
                                     </div>
                                   
                                 </div>
                                </div>
                            </div>
                           </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-4">
    
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <h5 class="card-title mb-0">Earnings By Item</h5>
                                            <div class="ms-auto">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="text-muted font-size-12">Sort By:</span> <span class="fw-medium">Weekly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body py-xl-0">
                                        <div id="earning-item" data-colors='["#33a186","#3980c0"]' class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <h5 class="card-title mb-0">Manage Orders</h5>
                                            <div class="ms-auto">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="text-muted font-size-12">Sort By: </span> <span class="fw-medium"> Weekly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                        <a class="dropdown-item" href="#">Monthly</a>
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-xl-1">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-centered align-middle table-nowrap mb-0">
                                                <thead >
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Product's Name</th>
                                                        <th>Variant</th>
                                                        <th>Type</th>
                                                        <th>Stock</th>
                                                        <th>Price</th>
                                                        <th>Sales</th>
                                                        <th>Status</th>
                                                        <th>Edit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1.</td>
                                                        <td><a href="javascript: void(0);" class="text-body">Iphone 12 Max Pro</a> </td>
                                                        <td>
                                                            <i class="mdi mdi-circle font-size-10 me-1 align-middle text-secondary"></i> Gray
                                                        </td>
                                                        <td>
                                                            Electronic
                                                        </td>
                                                        <td>
                                                           1,564 Items
                                                        </td>
                                                        <td>
                                                            $1200
                                                        </td>
                                                        <td>
                                                            900
                                                        </td>
                                                        
                                                        <td style="width: 130px;">
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>
                        
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                        
                                                    <tr>
                                                        <td>2.</td>
                                                        <td><a href="javascript: void(0);" class="text-body">New Red and White jacket </a> </td>
                                                        <td>
                                                            <i class="mdi mdi-circle font-size-10 me-1 align-middle text-danger"></i> Red
                                                        </td>
                                                        <td>
                                                            Fashion
                                                        </td>
                                                        <td>
                                                           568 Items
                                                        </td>
                                                        <td>
                                                            $300
                                                        </td>
                                                        <td>
                                                            650
                                                        </td>
                                                        
                                                        <td style="width: 130px;">
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="75">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>
                        
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                        
                                                    <tr>
                                                        <td>3.</td>
                                                        <td><a href="javascript: void(0);" class="text-body">Latest Series Watch OS 8</a> </td>
                                                        <td>
                                                            <i class="mdi mdi-circle font-size-10 me-1 align-middle text-primary"></i> Dark
                                                        </td>
                                                        <td>
                                                            Electronic
                                                        </td>
                                                        <td>
                                                           1,232 Items
                                                        </td>
                                                        <td>
                                                            $250
                                                        </td>
                                                        <td>
                                                            350
                                                        </td>
                                                        
                                                        <td style="width: 130px;">
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="75">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>
                        
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4.</td>
                                                        <td><a href="javascript: void(0);" class="text-body">New Horror Book</a> </td>
                                                        <td>
                                                            <i class="mdi mdi-circle font-size-10 me-1 align-middle text-success"></i> Green
                                                        </td>
                                                        <td>
                                                            Book
                                                        </td>
                                                        <td>
                                                           1,564 Items
                                                        </td>
                                                        <td>
                                                            $1200
                                                        </td>
                                                        <td>
                                                            900
                                                        </td>
                                                        
                                                        <td style="width: 130px;">
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="75">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>
                        
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>5.</td>
                                                        <td><a href="javascript: void(0);" class="text-body">Smart 4k Android TV</a> </td>
                                                        <td>
                                                            <i class="mdi mdi-circle font-size-10 me-1 align-middle text-primary"></i> Gray
                                                        </td>
                                                        <td>
                                                            Electronic
                                                        </td>
                                                        <td>
                                                           5,632 Items
                                                        </td>
                                                        <td>
                                                            $700
                                                        </td>
                                                        <td>
                                                            600
                                                        </td>
                                                        
                                                        <td style="width: 130px;">
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar progress-bar-striped bg-pricing" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="75">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </a>
                        
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Action</a>
                                                                    <a class="dropdown-item" href="#">Another action</a>
                                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->


@endsection
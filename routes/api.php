<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\API\AdminDashboardController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PlaceOrderController;
use App\Http\Controllers\API\PlaceOrderItemController;
use App\Http\Controllers\API\ProductController;

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\MembershipCardController;
use App\Http\Controllers\MembershipCardTypeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\UserPackageController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StoreAccessMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStoreController;


Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/employee-login', [EmployeeController::class, 'login']);

Route::get('/store/{store_id}/blog-list',[PostController::class,'getBlogList']);
Route::get('/store/{store_id}/blog-details/{slug}',[PostController::class,'blogDetails']);
Route::get('store/{store_id}/payment-method',[PlaceOrderController::class,'getPaymentMethod']);

Route::post('/store/{store_id}/advance-payment-process', [AppointmentController::class,'savePaymentData']);


Route::get('/store/{store_id}/get-service-category-list', [ServiceCategoryController::class, 'getServiceCategoryList']);
Route::get('/store/{store_id}/get-service-by-category_id/{category_id}', [ProductController::class, 'getServiceProduct']);
Route::get('/store/{store_id}/get-service-by-service-type', [ProductController::class, 'getServiceProductByService_type']);
Route::apiResource('/seo', SeoController::class);
Route::group(['prefix' => 'store/{store_id}'], static function () {
    Route::apiResource('/post', PostController::class);
    Route::post('/appointment-store', [AppointmentController::class,'store']);
    Route::apiResource('/post-category', PostCategoryController::class);
    Route::apiResource('/comment', CommentController::class);
    Route::apiResource('/advance-payment-methods', PaymentMethodController::class);

});
//    Route::get('/get-service-category',[ServiceCategoryController::class,'getServiceCategory']);
//    Route::put('/update-service-category/{id}',[ServiceCategoryController::class,'updateServiceCategory']);
//    Route::delete('/delete-service-category/{id}',[ServiceCategoryController::class,'deleteServiceCategory']);
Route::get('/get-service-category', [ServiceCategoryController::class, 'getServiceCategoryAssoC']);
Route::get('/serviceCategory-withCategory', [ServiceCategoryController::class, 'getServiceCategoryWithCategory']);
Route::get('/get-service-by-category/{category_id}', [ProductController::class, 'getServiceByCategoryId']);


Route::group(['middleware' => 'admin'], static function () {

    Route::group(['prefix' => 'store/{store_id}','middleware'=>'storeAccess'], function () {
        Route::apiResource('/service-category', ServiceCategoryController::class);

    });


    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::put('/admin/{id}', [UserController::class, 'update']);
    Route::post('/stores/{store_id}/add-employee', [EmployeeController::class, 'register'])->middleware(StoreAccessMiddleware::class);
    Route::get('/admin/employee/allemployee', [EmployeeController::class, 'my_all_employee']);
    Route::get('/admin/employee/profile/{id}', [EmployeeController::class, 'employee_profile']);
    Route::put('/admin/employee/profile/{id}', [EmployeeController::class, 'update']);
    Route::delete('/admin/employee/profile/{id}', [EmployeeController::class, 'destroy']);

    Route::delete('/stores/{store_id}/orders/{order_id}', [PlaceOrderController::class, 'deleteOrder'])->middleware(StoreAccessMiddleware::class);

    Route::post('/admin/search_admin/{id}/search', [UserStoreController::class, 'searchUserByEmail']);
    Route::get('/admin/aalladmin/{id}', [UserStoreController::class, 'index']);
    Route::post('/admin/addadmin/{id}/{userId}', [UserStoreController::class, 'store']);



    Route::post('/stores', [StoreController::class, 'store']);
    Route::put('/stores/{id}', [StoreController::class, 'update']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);



    Route::get('/stores/{store_id}/categories', [CategoryController::class, 'index'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/category-list', [CategoryController::class, 'category_list'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/category/{category_id}', [CategoryController::class, 'show'])->middleware(StoreAccessMiddleware::class);
    Route::post('/stores/{store_id}/add-category', [CategoryController::class, 'store'])->middleware(StoreAccessMiddleware::class);
    Route::put('/stores/category/{cat_id}', [CategoryController::class, 'update']);
    Route::delete('/stores/category/{cat_id}', [CategoryController::class, 'destroy']);

    Route::put('/stores/products/{product_id}', [ProductController::class, 'update']);
    Route::delete('/stores/products/{product_id}', [ProductController::class, 'destroy']);
    //storeID change to store_id
    Route::post('/stores/{store_id}/product/add-product-or-service', [ProductController::class, 'product_store_post'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/category/{cat_id}/category-all-products-and-service', [ProductController::class, 'category_all_product_and_service'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/category/{cat_id}/category-all-service', [ProductController::class, 'category_all_service'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/category/{cat_id}/category-all-products', [ProductController::class, 'category_all_product'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/product-details/{product_id}', [ProductController::class, 'store_product_show'])->middleware(StoreAccessMiddleware::class);

    Route::get('/order_details/{order_id}', [PlaceOrderController::class, 'showOrder']);

    Route::get('/admin_dashboard_data/{store_id}', [AdminDashboardController::class, 'admin_dashboard_data'])->middleware(StoreAccessMiddleware::class);
    Route::get('stores/{store_id}/customers', [AdminDashboardController::class, 'customers'])->middleware(StoreAccessMiddleware::class);
    Route::get('stores/{store_id}/totalSaleProductNumberMonth/{startDate}/{endDate?}', [AdminDashboardController::class, 'totalSaleProductNumberMonth'])->middleware(StoreAccessMiddleware::class);
    Route::get('stores/{store_id}/totalSaleProductNumber', [AdminDashboardController::class, 'totalSaleProductNumber'])->middleware(StoreAccessMiddleware::class);
    Route::get('stores/{store_id}/total-order-list', [AdminDashboardController::class, 'totalOrder'])->middleware(StoreAccessMiddleware::class);
    Route::get('/order-items/{id}', [PlaceOrderItemController::class, 'showOrderItem']);
    Route::resource('stores/{store_id}/membership-card-type', MembershipCardTypeController::class)->middleware(StoreAccessMiddleware::class);
    Route::get('stores/{store_id}/get-membership-card-type-list-for-dropdown', [MembershipCardTypeController::class, 'membership_card_list'])->middleware(StoreAccessMiddleware::class);
    Route::resource('stores/{store_id}/membership-card', MembershipCardController::class)->middleware(StoreAccessMiddleware::class);
    Route::get('stores/{store_id}/top-customer',[AdminDashboardController::class,'topCustomer'])->middleware(StoreAccessMiddleware::class);
    Route::get('stores/{store_id}/top-product', [AdminDashboardController::class,'topProduct'])->middleware(StoreAccessMiddleware::class);



});

Route::group(['middleware' => 'employee'], function () {

});

Route::group(['middleware' => 'adminOrEmployee'], function () {
    Route::post('/orders', [PlaceOrderController::class, 'placeOrder']);
    Route::group(['prefix' => 'store/{store_id}','middleware'=>'storeAccess'], function () {
        Route::get('/get-service-category-lists', [ServiceCategoryController::class, 'getServiceCategoryList']);
        Route::apiResource('/appointment', AppointmentController::class);

        Route::get('/get-service-byCategory_id/{category_id}', [ProductController::class, 'getServiceProduct']);
        Route::post('/orders', [PlaceOrderController::class, 'placeOrder']);

    });
    Route::post('/store/{store_id}/payment-process', [AppointmentController::class,'savePaymentData'])->middleware(StoreAccessMiddleware::class);
    Route::apiResource('/store/{store_id}/payment-methods', PaymentMethodController::class)->middleware(StoreAccessMiddleware::class);



    Route::get('/stores/{store_id}/all-products-and-service', [ProductController::class, 'show'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/all-products', [ProductController::class, 'productshow'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/all-service', [ProductController::class, 'serviceshow'])->middleware(StoreAccessMiddleware::class);
    Route::get('/order-details/{order_id}', [PlaceOrderController::class, 'orderDetails']);
    Route::get('/stores', [StoreController::class, 'index']);

    Route::get('/order/{store_id}/{order_id}', [PlaceOrderController::class, 'getOrderDetails'])->middleware(StoreAccessMiddleware::class);
    Route::get('/all_orders/{store_id?}', [PlaceOrderController::class, 'index'])->middleware(StoreAccessMiddleware::class);

    Route::get('/user-details', [UserController::class, 'userProfile']);

    Route::get('/store/{store_id}/appointment-dashboard',[AppointmentController::class,'appointment_dashboard'])->middleware(StoreAccessMiddleware::class);
    Route::get('/store/{store_id}/appointment-calender',[AppointmentController::class,'appointment_calender'])->middleware(StoreAccessMiddleware::class);
    Route::get('/store/{store_id}/appointment-calender-by-date',[AppointmentController::class,'by_date_appointment'])->middleware(StoreAccessMiddleware::class);


    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/store/opening-price/{store_id}', [AccountsController::class, 'getprice'])->middleware(StoreAccessMiddleware::class);
    Route::get('/store/daily-sale/{store_id}', [AccountsController::class, 'dailysale'])->middleware(StoreAccessMiddleware::class);
    Route::get('/store/sale-list/{store_id}', [AccountsController::class, 'saleList'])->middleware(StoreAccessMiddleware::class);
    Route::get('/store/daily-sale-list/{store_id}', [AccountsController::class, 'daily_sale_list'])->middleware(StoreAccessMiddleware::class);
    Route::post('/stores/opening-price/{store_id}', [AccountsController::class, 'register'])->middleware(StoreAccessMiddleware::class);
    Route::get('/store/weekly-sale-list/{store_id}', [AccountsController::class, 'weekly_sale_list'])->middleware(StoreAccessMiddleware::class);
    Route::get('/store/monthly-sale-list/{store_id}', [AccountsController::class, 'monthly_sale_list'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}', [StoreController::class, 'show'])->middleware(StoreAccessMiddleware::class);
    Route::get('/stores/{store_id}/get-customer-details', [CustomerController::class, 'getCustomerDetails'])->middleware(StoreAccessMiddleware::class);
    Route::apiresource('/stores/{store_id}/customer', CustomerController::class)->middleware(StoreAccessMiddleware::class);
    Route::apiresource('/packages', PackageController::class);
    Route::apiresource('/features', FeatureController::class);
    Route::put('/feature-rearrange',[FeatureController::class,'rearrangeFeature']);
    Route::apiResource('/stores/{store_id}/expense', ExpenseController::class);
    Route::apiResource('/blog', BlogController::class);


});

Route::delete('/stores/{store_id}/expense', [ExpenseController::class,'destroy'])->middleware(AdminMiddleware::class);

Route::apiresource('/all-packages', PackageController::class);
Route::apiresource('/all-features', FeatureController::class);

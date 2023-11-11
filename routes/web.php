<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



    Route::get('/login', [SuperadminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [SuperadminController::class, 'login']);

    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');

    Route::get('/register', [UserController::class, 'admin_register'])->name('admin.register');
    Route::post('/register', [UserController::class, 'superadmin_register_admin'])->name('admin_registerpage');

    Route::get('/admin_edit/{user_id}', [UserController::class, 'admin_edit_page'])->name('admin_edit.page');
    Route::post('/admin_edit', [UserController::class, 'superadmin_update_admin'])->name('admin_edit_superadmin');
    Route::get('/admin_details', [UserController::class, 'admin_details'])->name('admin.details');
    Route::get('/admin_details_by_id/{id}', [UserController::class, 'admin_details_by_id'])->name('detail_admin');
    Route::get('/delete_admin', [UserController::class, 'delete_by_superadmin'])->name('delete.admin');

    Route::get('/store_details', [StoreController::class, 'store_details'])->name('store.details');
    Route::get('/add_store/{user_id?}/{store_id?}', [StoreController::class, 'addstorepage'])->name('addstore.page');
    Route::post('/add_store', [StoreController::class, 'addstore'])->name('add.store');
    Route::get('/edit_store/{user_id}/{store_id}', [StoreController::class, 'superadmin_edit_store'])->name('adminstore.edit');
    Route::post('/edit_store', [StoreController::class, 'superadmin_edit_store_post'])->name('update.store');
    Route::get('/delete_store', [StoreController::class, 'delete_by_superadmin'])->name('adminstore.delete');

    Route::get('/add_category/{user_id?}/{store_id?}', [CategoryController::class, 'addcategorypage'])->name('addcategorypage');
    Route::get('/edit_category/{cat_id}', [CategoryController::class, 'superadmin_edit_category'])->name('edit.catgory');
    Route::post('/edit_category', [CategoryController::class, 'superadmin_edit_Category_post'])->name('superadmin_edit_Category_post');
    Route::post('/add_category', [CategoryController::class, 'addcategory'])->name('addcategory');
    Route::get('/delete_category', [CategoryController::class, 'delete_by_superadmin'])->name('deleteCategory');

    Route::get('/add_product/{user_id?}/{store_id?}/{category_id?}', [ProductController::class, 'add_product'])->name('add_product');
    Route::get('/edit_product/{product_id}', [ProductController::class, 'superadmin_edit_product'])->name('edit.product');
    Route::post('/edit_product', [ProductController::class, 'superadmin_edit_product_post'])->name('edit.product_post');
    Route::post('/add_product', [ProductController::class, 'product_store_post_superadmin'])->name('product_store_post');
    Route::get('/product_details', [ProductController::class, 'product_details'])->name('product.details');
    Route::get('/delete_product', [ProductController::class, 'delete_by_superadmin'])->name('product.delete');

    Route::get('/payment',[HomeController::class,'showPaymentPage']);
    Route::get('/success',[HomeController::class,'showSuccessPage'])->name('success');
    Route::get('/failed',[HomeController::class,'showFailedPage'])->name('failed');
    Route::post('/process-payment',[HomeController::class,'processPayment']);






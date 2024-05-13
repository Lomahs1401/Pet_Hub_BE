<?php

use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public route
Route::group([
    'middleware' => ['force.json.response', 'api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::patch('/reset-password/request', [AccountController::class, 'requestSendResetCode']);
    Route::patch('/reset-verify-code/request', [AccountController::class, 'resetVerifyCode']);
    Route::patch('/reset-password/{email}/{code}', [AccountController::class, 'resetPassword']);
});

// Auth API
Route::group([
    'middleware' => ['force.json.response', 'api', 'auth'],
    'prefix' => 'auth',
], function ($router) {
    // Authenticate
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::patch('/changePassword', [AccountController::class, 'changePassword']);
});

// Customer API
Route::group([
    'middleware' => ['force.json.response', 'api', 'auth', 'auth.customer'],
    'prefix' => 'customer',
], function ($router) {
    // Product
    Route::get('/products', [ProductController::class, 'index']); // lấy tất cả product
    Route::get('/products/shop/{shop_id}', [ProductController::class, 'getListProductByShopId']); // lấy dsach các product được bán bởi shop
    Route::get('/products/shop/{shop_id}/total', [ProductController::class, 'getNumberOfProductByShopId']); // lấy số lượng product mà shop đang bán
    Route::get('/products/category/{category_id}', [ProductController::class, 'getListProductByCategoryId']); // lấy dsach các product thuộc category
    Route::get('/products/category/{category_id}/total', [ProductController::class, 'getNumberOfProductByCategoryId']); // lấy số lượng product thuộc category
    Route::get('/products/shop/distinct/{category_id}', [ProductController::class, 'getNumberOfShopSellingByCategory']); // lấy số lượng các shop bán product thuộc category
    Route::get('/products/shop/{shop_id}/category/{category_id}', [ProductController::class, 'getListProductWithShopAndCategory']); // lấy ds các product được bán bởi shop và thuộc category_id
    Route::get('/products/shop/{shop_id}/category/{category_id}/total', [ProductController::class, 'getNumberOfProductWithShopAndCategory']); // lấy số lượng product được bán bởi shop và thuộc category_id
    Route::get('/products/sort/{order}', [ProductController::class, 'sortProductsByPrice']);
    Route::get('/products/paginate', [ProductController::class, 'paging']);
    Route::get('/products/{id}', [ProductController::class, 'show']); // lấy chi tiết product by id
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::get('/blog-categories', [BlogController::class, 'index']);
    Route::get('/blog-categories/count', [BlogController::class, 'countCategories']);
    Route::get('/blog-categories/count-blogs', [BlogController::class, 'countBlogsByCategory']);
    Route::post('/blog-categories', [BlogController::class, 'store']);
    Route::put('/blog-categories/{id}', [BlogController::class, 'update']);
    Route::delete('/blog-categories/{id}', [BlogController::class, 'destroy']);


});



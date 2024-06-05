<?php

use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\BreedController;
use App\Http\Controllers\API\MedicalCenterController;
use App\Http\Controllers\API\PetController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RatingProductController;
use App\Http\Controllers\API\ServiceController;
use App\Models\MedicalCenter;
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
  Route::post('/login', [AuthController::class, 'login']);
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/refresh', [AuthController::class, 'refresh']);
  Route::patch('/reset-password/request', [AccountController::class, 'requestSendResetCode']);
  Route::patch('/reset-verify-code/request', [AccountController::class, 'resetVerifyCode']);
  Route::patch('/reset-password/{email}/{code}', [AccountController::class, 'resetPassword']);
});

// Auth API
Route::group([
  'middleware' => ['force.json.response', 'api', 'auth.user'],
  'prefix' => 'auth',
], function ($router) {
  // Authenticate
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::post('/profile', [AuthController::class, 'profile']);
  Route::patch('/changePassword', [AccountController::class, 'changePassword']);
});

// Customer API
Route::group([
  'middleware' => ['force.json.response', 'api', 'auth.user', 'auth.customer'],
  'prefix' => 'customer',
], function ($router) {
  // --------------     PRODUCT     --------------
  Route::get('/products', [ProductController::class, 'index']); // lấy tất cả product
  Route::get('/products/shop/{shop_id}', [ProductController::class, 'getListProductByShopId']); // lấy dsach các product được bán bởi shop
  Route::get('/products/shop/{shop_id?}/total', [ProductController::class, 'getNumberOfProductByShopId']); // lấy số lượng product mà shop đang bán
  Route::get('/products/category/{product_category_id}', [ProductController::class, 'getListProductByCategoryId']); // lấy dsach các product thuộc category
  Route::get('/products/category/{product_category_id}/total', [ProductController::class, 'getNumberOfProductByCategoryId']); // lấy số lượng product thuộc category
  Route::get('/products/shop/distinct/category/{product_category_id}', [ProductController::class, 'getNumberOfShopSellingByCategory']); // lấy số lượng các shop bán product thuộc category
  Route::get('/products/shop/{shop_id}/category/{product_category_id}', [ProductController::class, 'getListProductWithShopAndCategory']); // lấy ds các product được bán bởi shop và thuộc category_id
  Route::get('/products/shop/{shop_id?}/category/{product_category_id}/total', [ProductController::class, 'getNumberOfProductWithShopAndCategory']); // lấy số lượng product được bán bởi shop và thuộc category_id
  Route::get('/products/sort', [ProductController::class, 'sortProductsByPrice']);
  Route::get('/products/search', [ProductController::class, 'searchProduct']);
  // --------------     PRODUCT PAGINATION     --------------
  Route::get('/products/paginate', [ProductController::class, 'paging']); // lấy ds product có phân trang (query param: page_number, num_of_page, target)
  Route::get('/products/best-selling', [ProductController::class, 'getBestSellingProduct']); // lấy ds product bán chạy (ko ràng buộc bởi shop và category)
  Route::get('/products/best-selling/shop/{shop_id?}', [ProductController::class, 'getBestSellingProductByShop']); // lấy ds product bán chạy bởi shop
  Route::get('/products/best-selling/category/{product_category_id}', [ProductController::class, 'getBestSellingProductByCategory']); // lấy ds product bán chạy thuộc category
  Route::get('/products/best-selling/shop/{shop_id}/category-type', [ProductController::class, 'getBestSellingProductWithShopAndCategoryType']); // lấy ds product bán chạy bởi shop và thuộc category type
  Route::get('/products/best-selling/shop/{shop_id?}/category/{product_category_id}', [ProductController::class, 'getBestSellingProductWithShopAndCategory']); // lấy ds product bán chạy bởi shop và thuộc category
  Route::get('/products/highest-rating', [ProductController::class, 'getHighestRatingProduct']); // lấy ds product được đánh giá cao nhất (ko ràng buộc bởi shop và category)
  Route::get('/products/highest-rating/shop/{shop_id?}', [ProductController::class, 'getHighestRatingProductByShop']); // lấy ds product có điểm đánh giá cao nhất thuộc shop
  Route::get('/products/highest-rating/category/{product_category_id}', [ProductController::class, 'getHighestRatingProductByCategory']); // lấy ds product có điểm đánh giá cao nhất thuộc category
  Route::get('/products/highest-rating/shop/{shop_id?}/category/{product_category_id}', [ProductController::class, 'getHighestRatingProductWithShopAndCategory']); // lấy ds product có điểm đánh giá cao nhất thuộc shop và category
  Route::get('/products/lowest-rating', [ProductController::class, 'getLowestRatingProduct']); // lấy ds product được đánh giá thấp nhất (ko ràng buộc bởi shop và category)
  Route::get('/products/lowest-rating/shop/{shop_id?}', [ProductController::class, 'getLowestRatingProductByShop']); // lấy ds product có điểm đánh giá thấp nhất thuộc shop
  Route::get('/products/lowest-rating/category/{product_category_id}', [ProductController::class, 'getLowestRatingProductByCategory']); // lấy ds product có điểm đánh giá thấp nhất thuộc category
  Route::get('/products/lowest-rating/shop/{shop_id?}/category/{product_category_id}', [ProductController::class, 'getLowestRatingProductWithShopAndCategory']); // lấy ds product có điểm đánh giá thấp nhất thuộc shop và category
  // ------------------------------------------------
  Route::get('/products/{id}', [ProductController::class, 'show']);

  // --------------     PRODUCT CATEGORY     --------------
  Route::get('/product-categories/shop/{shop_id}', [ProductCategoryController::class, 'getProductCountsByCategory']); // lấy sl product của category và thuộc shop_id
  Route::get('/product-categories/type/shop/{shop_id}', [ProductCategoryController::class, 'getProductCountsByCategoryType']); // lấy sl product của category type và thuộc shop_id


  // --------------     BLOG     --------------
  Route::get('/blog-categories', [BlogController::class, 'index']);
  Route::get('/blog-categories/count', [BlogController::class, 'countCategories']);
  Route::get('/blog-categories/count-blogs', [BlogController::class, 'countBlogsByCategory']);
  Route::post('/blog-categories', [BlogController::class, 'store']);
  Route::put('/blog-categories/{id}', [BlogController::class, 'update']);
  Route::delete('/blog-categories/{id}', [BlogController::class, 'destroy']);



  // --------------     SERVICE     --------------
  Route::get('/services', [ServiceController::class, 'index']); // lấy tất cả service
  Route::get('/services/medical-center/{medical_center_id}', [ServiceController::class, 'getListServiceByMedicalCenterId']); // lấy dsach các service được cung cấp bởi trung tâm y tế
  Route::get('/services/medical-center/{medical_center_id}/total', [ServiceController::class, 'getNumberOfServiceByMedicalCenterId']); // lấy số lượng service mà trung tâm y tế cung cấp
  Route::get('/services/category/{category_id}', [ServiceController::class, 'getListServiceByCategoryId']); // lấy dsach các service thuộc category
  Route::get('/services/category/{category_id}/total', [ServiceController::class, 'getNumberOfServiceByCategoryId']); // lấy số lượng service thuộc category
  Route::get('/services/medical-center/distinct/category/{category_id}', [ServiceController::class, 'getNumberOfMedicalCetnterProvideServiceByCategory']); // lấy số lượng các medical center cung cấp service thuộc category
  Route::get('/services/medical-center/{medical_center_id}/category/{category_id}', [ServiceController::class, 'getListServiceWithMedicalCenterAndCategory']); // lấy ds các service được cung cấp bởi medical center và thuộc category_id
  Route::get('/services/medical-center/{medical_center_id}/category/{category_id}/total', [ServiceController::class, 'getNumberOfServiceWithMedicalCenterAndCategory']); // lấy số lượng service được cung cấp bởi medical center và thuộc category_id
  Route::get('/services/sort', [ServiceController::class, 'sortServicesByPrice']);
  Route::get('/services/search', [ServiceController::class, 'searchService']);
  // --------------     SERVICE PAGINATION     --------------
  Route::get('/services/paginate', [ServiceController::class, 'paging']); // lấy ds service có phân trang (query param: page_number, num_of_page, target)
  Route::get('/services/best-selling', [ServiceController::class, 'getBestSellingService']); // lấy ds service bán chạy (ko ràng buộc bởi medical center và category)
  Route::get('/services/best-selling/medical-center/{medical_center_id}', [ServiceController::class, 'getBestSellingServiceByMedicalCenter']); // lấy ds service bán chạy bởi medical center
  Route::get('/services/best-selling/category/{category_id}', [ServiceController::class, 'getBestSellingServiceByCategory']); // lấy ds service bán chạy thuộc category
  Route::get('/services/best-selling/medical-center/{medical_center_id}/category/{category_id}', [ServiceController::class, 'getBestSellingServicetWithMedicalCenterAndCategory']); // lấy ds service bán chạy bởi medical center và thuộc category
  Route::get('/services/highest-rating', [ServiceController::class, 'getHighestRatingService']); // lấy ds service được đánh giá cao nhất (ko ràng buộc bởi medical center và category)
  Route::get('/services/highest-rating/medical-center/{medical_center_id}', [ServiceController::class, 'getHighestRatingServiceByMedicalCenter']); // lấy ds service có điểm đánh giá cao nhất thuộc medical center
  Route::get('/services/highest-rating/category/{category_id}', [ServiceController::class, 'getHighestRatingServiceByCategory']); // lấy ds service có điểm đánh giá cao nhất thuộc category
  Route::get('/services/highest-rating/medical-center/{medical_center_id}/category/{category_id}', [ServiceController::class, 'getHighestRatingServiceWithMedicalCenterAndCategory']); // lấy ds service có điểm đánh giá cao nhất thuộc medical center và category
  // --------------     SERVICE SOFT DELETE     --------------
  Route::get('/services/deleted', [ServiceController::class, 'getDeletedServices']);
  Route::put('/services/{id}/restore', [ServiceController::class, 'restore']);
  // ------------------------------------------------
  Route::get('/services/{id}', [ServiceController::class, 'show']);
  Route::post('/services', [ServiceController::class, 'store']);
  Route::put('/services/{id}', [ServiceController::class, 'update']);
  Route::delete('/services/{id}', [ServiceController::class, 'destroy']);


  // --------------     RATING     --------------
  Route::get('/ratings/product/{product_id}', [RatingProductController::class, 'getCustomerRatingsOfProductId']); // lấy dsach rating của customer theo product id

  // --------------     MEDICAL CENTER　PAGINATION     --------------
  Route::get('/medical-centers/paginate', [MedicalCenterController::class, 'paging']);
  Route::get('/medical-centers/search', [MedicalCenterController::class, 'searchMedicalCenter']);
  Route::get('/medical-centers/highest-rating', [MedicalCenterController::class, 'getHighestRatingMedicalCenter']);
  // ------------------------------------------------
  Route::get('/medical-centers/{medical_center_id}', [MedicalCenterController::class, 'show']);

  // --------------     APPOINTMENT     --------------
  Route::get('/appointments/paginate', [AppointmentController::class, 'paging']);
  Route::get('/appointments/before-current-date', [AppointmentController::class, 'getAppointmentBeforeCurrentDate']);
  Route::get('/appointments/after-current-date', [AppointmentController::class, 'getAppointmentAfterCurrentDate']);
  Route::get('/appointments/done', [AppointmentController::class, 'getDoneAppointment']);
  Route::get('/appointments/deleted', [AppointmentController::class, 'getDeletedAppointment']);
  // ------------------------------------------------
  Route::post('/appointments', [AppointmentController::class, 'store']);
  Route::get('/appointments/{appointment_id}', [AppointmentController::class, 'show']);
  Route::put('/appointments/{appointment_id}', [AppointmentController::class, 'update']);
  Route::delete('/appointments/{appointment_id}', [AppointmentController::class, 'destroy']);

  // --------------     PET     --------------
  Route::get('/pets/paginate', [PetController::class, 'pagingCustomerPet']);
  Route::get('/pets/adopted/paginate', [PetController::class, 'pagingAdoptedPet']);
  Route::get('/pets/all/paginate', [PetController::class, 'pagingAllPet']);
  Route::get('/pets/search', [PetController::class, 'searchPet']);
  Route::get('/pets/deleted', [PetController::class, 'getDeletedPet']);
  // --------------     PET SOFT DELETE     --------------
  Route::put('/pets/{pet_id}/restore', [PetController::class, 'restore']);
  Route::delete('/pets/{pet_id}', [PetController::class, 'destroy']);
  // ------------------------------------------------
  Route::get('/pets/{pet_id}', [PetController::class, 'show']);
  Route::post('/pets', [PetController::class, 'store']);
  Route::put('/pets/{pet_id}', [PetController::class, 'update']);

  // --------------     BREED     --------------
  Route::get('/breeds', [BreedController::class, 'index']);
  Route::get('/breeds/{breed_id}', [BreedController::class, 'show']);
});

// Shop API
Route::group([
  'middleware' => ['force.json.response', 'api', 'auth.user', 'auth.shop'],
  'prefix' => 'shop',
], function ($router) {
  // --------------     PRODUCT     --------------
  Route::get('/products/total', [ProductController::class, 'getNumberOfProductByShopId']); // lấy số lượng product mà shop đang bán
  Route::get('/products/category/{product_category_id}/total', [ProductController::class, 'getNumberOfProductWithShopAndCategory']); // lấy số lượng product được bán bởi shop và thuộc category_id
  // --------------     PRODUCT PAGINATION     --------------
  Route::get('/products/sort', [ProductController::class, 'sortProductsByPrice']);
  Route::get('/products/search', [ProductController::class, 'searchProduct']);
  Route::get('/products/search-deleted', [ProductController::class, 'searchDeletedProduct']);
  Route::get('/products/paginate', [ProductController::class, 'paging']); // lấy ds product có phân trang (query param: page_number, num_of_page, target)
  Route::get('/products/best-selling', [ProductController::class, 'getBestSellingProductByShop']); // lấy ds product bán chạy bởi shop
  Route::get('/products/best-selling/category/{product_category_id}', [ProductController::class, 'getBestSellingProductByCategory']); // lấy ds product bán chạy bởi shop và thuộc category
  Route::get('/products/highest-rating', [ProductController::class, 'getHighestRatingProductByShop']); // lấy ds product có điểm đánh giá cao nhất thuộc shop
  Route::get('/products/highest-rating/category/{product_category_id}', [ProductController::class, 'getHighestRatingProductByCategory']); // lấy ds product có điểm đánh giá cao nhất thuộc category
  Route::get('/products/lowest-rating', [ProductController::class, 'getLowestRatingProductByShop']); // lấy ds product có điểm đánh giá thấp nhất thuộc shop
  Route::get('/products/lowest-rating/category/{product_category_id}', [ProductController::class, 'getLowestRatingProductByCategory']); // lấy ds product có điểm đánh giá thấp nhất thuộc category
  Route::get('/products/sold-out', [ProductController::class, 'getSoldOutProducts']); // lấy ds product đã bán hết
  Route::get('/products/rating', [ProductController::class, 'getProductsByRating']); // lấy ds product có điểm đánh giá cao nhất thuộc shop
  Route::get('/products/deleted', [ProductController::class, 'getDeletedProducts']);
  // --------------     PRODUCT SOFT DELETE     --------------
  Route::put('/products/{id}/restore', [ProductController::class, 'restore']);
  Route::delete('/products/{id}', [ProductController::class, 'destroy']);
  // ------------------------------------------------
  Route::get('/products/{id}', [ProductController::class, 'show']);
  Route::post('/products', [ProductController::class, 'store']);
  Route::put('/products/{id}', [ProductController::class, 'update']);

  // --------------     PRODUCT CATEGORY     --------------
  Route::get('/product-categories/products', [ProductCategoryController::class, 'getProductCountsByCategory']); // lấy sl product của category và thuộc shop_id
  Route::get('/product-categories/type/products', [ProductCategoryController::class, 'getProductCountsByCategoryType']); // lấy sl product của category type và thuộc shop_id
  Route::get('/product-categories/type', [ProductCategoryController::class, 'getDistinctCategoryTypes']); // lấy dsach category type
  Route::get('/product-categories/type/{target}', [ProductCategoryController::class, 'getDistinctCategoryTypesByTarget']); // lấy dsach category type theo target

  // --------------     RATING     --------------
  Route::get('/ratings/product/{product_id}', [RatingProductController::class, 'getCustomerRatingsOfProductId']); // lấy dsach rating của customer theo product id
  Route::get('/ratings/product/{product_id}/detail', [RatingProductController::class, 'getDetailRating']); // lấy dsach các loại đánh giá (5,4,3,2,1 sao) của customer theo product id
  Route::post('/rating-products/{rating_product_id}/like', [RatingProductController::class, 'likeRatingProduct']);
  Route::post('/rating-products/{rating_product_id}/unlike', [RatingProductController::class, 'unlikeRatingProduct']);
  Route::post('/rating-products/{rating_product_id}/reply', [RatingProductController::class, 'replyToRatingProduct']);
  Route::put('/rating-products/{rating_product_id}/reply', [RatingProductController::class, 'updateReplyToRatingProduct']);
  Route::delete('/rating-products/{rating_product_id}/reply', [RatingProductController::class, 'deleteReplyToRatingProduct']);
});

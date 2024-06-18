<?php

use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\BreedController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CartItemController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\MedicalCenterController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PetController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RatingProductController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\ShopDashboardController;
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
  Route::patch('/change-password', [AccountController::class, 'changePassword']);
});

// Customer API
Route::group([
  'middleware' => ['force.json.response', 'api', 'auth.user', 'auth.customer'],
  'prefix' => 'customer',
], function ($router) {
  // --------------     CUSTOMER     --------------
  Route::put('/profile', [CustomerController::class, 'updateCustomer']);

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
  Route::get('/products/best-selling/category-type', [ProductController::class, 'getBestSellingProductByCategoryType']);
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
  Route::get('/blogs', [BlogController::class, 'getBlogs']);
  Route::get('/blogs/me/', [BlogController::class, 'getMyBlogs']);
  Route::get('/blogs/{blog_id}', [BlogController::class, 'getBlogDetail']);
  Route::post('/blogs', [BlogController::class, 'createBlog']);
  Route::put('/blogs/{blog_id}', [BlogController::class, 'updateBlog']);
  Route::delete('/blogs/{blog_id}', [BlogController::class, 'deleteBlog']);

  Route::get('/blog-categories', [BlogController::class, 'index']);
  Route::get('/blog-categories/count-blogs', [BlogController::class, 'countBlogsByCategory']);

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
  Route::get('/appointments/free', [AppointmentController::class, 'getFreeAppointment']);

  // ------------------------------------------------
  Route::post('/appointments', [AppointmentController::class, 'store']);
  Route::get('/appointments/{appointment_id}', [AppointmentController::class, 'show']);
  Route::put('/appointments/{appointment_id}', [AppointmentController::class, 'update']);
  Route::delete('/appointments/{appointment_id}', [AppointmentController::class, 'destroy']);

  // --------------     PET     --------------
  Route::get('/pets/paginate/{customer_id?}', [PetController::class, 'pagingCustomerPet']);
  Route::get('/pets/adopted/paginate/{customer_id?}', [PetController::class, 'pagingAdoptedPet']);
  Route::get('/pets/all/paginate/{customer_id?}', [PetController::class, 'pagingAllPet']);
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

  // --------------     DOCTOR     --------------
  Route::get('/doctors/medical-center/{medical_center_id}/paging', [DoctorController::class, 'getDoctorsOfMedicalCenter']);
  Route::get('/doctors/{doctor_id}', [DoctorController::class, 'show']);
  Route::get('/doctors/{doctor_id}/appointments', [DoctorController::class, 'getAllAppointmentsOfDoctor']);
  Route::get('/doctors/{doctor_id}/freetime', [DoctorController::class, 'getFreetimeOfDoctor']);

  // --------------     ORDER     --------------
  Route::get('/orders', [OrderController::class, 'getOrders']);
  Route::get('/orders/{order_id}', [OrderController::class, 'getOrderDetail']);
  Route::post('/orders', [OrderController::class, 'createOrder']);

  // --------------     CART     --------------
  Route::get('/carts', [CartController::class, 'getCurrentCart']);
  Route::post('/carts', [CartController::class, 'createNewCart']);
  Route::put('/carts/{cart_id}', [CartController::class, 'updateCart']);
  // --------------     CART ITEM    --------------
  Route::post('/cart-items/{cart_id}', [CartItemController::class, 'addProductIntoCart']);
  Route::post('/cart-items/{cart_item_id}/deleted', [CartItemController::class, 'removeProductFromCart']);
  Route::post('/cart-items/{cart_id}/increase', [CartItemController::class, 'increaseQuantityOfProductInCart']);
  Route::post('/cart-items/{cart_id}/decrease', [CartItemController::class, 'decreaseQuantityOfProductInCart']);
});

// Shop API
Route::group([
  'middleware' => ['force.json.response', 'api', 'auth.user', 'auth.shop'],
  'prefix' => 'shop',
], function ($router) {
  // --------------     DASHBOARD     --------------
  Route::get('/banner/reviews', [ShopDashboardController::class, 'getReviewsComparison']);
  Route::get('/banner/replies', [ShopDashboardController::class, 'getRepliesComparison']);
  Route::get('/banner/products', [ShopDashboardController::class, 'getProductsComparison']);
  Route::get('/banner/orders', [ShopDashboardController::class, 'getOrdersComparison']);
  Route::get('/banner/sales', [ShopDashboardController::class, 'getSales']);
  Route::get('/bar/revenue', [ShopDashboardController::class, 'getRevenue']);
  Route::get('/bar/selled', [ShopDashboardController::class, 'getSelled']);
  Route::get('/pie/product-category', [ShopDashboardController::class, 'getCategory']);
  Route::get('/recent-orders', [ShopDashboardController::class, 'getRecentOrder']);
  Route::get('/popular-products', [ShopDashboardController::class, 'getPopularProduct']);

  // --------------     SHOP     --------------
  Route::get('/profile', [ShopController::class, 'getProfileOfShop']);
  Route::put('/profile', [ShopController::class, 'updateShop']);

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
  Route::get('/products/overview', [ProductController::class, 'getProductOverview']);
  Route::get('/products/latest-id', [ProductController::class, 'getLatestProductId']);

  // --------------     PRODUCT SOFT DELETE     --------------
  Route::put('/products/{id}/restore', [ProductController::class, 'restore']);
  Route::delete('/products/{id}', [ProductController::class, 'destroy']);
  // ------------------------------------------------
  Route::get('/products/{id}', [ProductController::class, 'show']);
  Route::post('/products', [ProductController::class, 'store']);
  Route::put('/products/{id}', [ProductController::class, 'update']);

  // --------------     PRODUCT CATEGORY     --------------
  Route::get('/product-categories', [ProductCategoryController::class, 'getProductCategories']);
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

  // --------------     ORDER     --------------
  Route::get('/orders', [OrderController::class, 'getOrders']);
  Route::get('/orders/paging', [OrderController::class, 'pagingOrders']);
  Route::get('/orders/revenue/{product_id}', [OrderController::class, 'getRevenueByOrder']);
  Route::get('/orders/selling/{product_id}', [OrderController::class, 'getSellingByOrder']);
  Route::get('/orders/{order_id}', [OrderController::class, 'getOrderDetail']);
  Route::post('/orders', [OrderController::class, 'createOrder']);

  // --------------     SUB ORDER     --------------
  Route::get('/sub-orders/{sub_order_id}', [OrderController::class, 'getSubOrders']);
});

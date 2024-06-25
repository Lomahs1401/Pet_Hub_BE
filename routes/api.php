<?php

use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\AdminAidCenterController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AdminDashboardController;
use App\Http\Controllers\API\AdminMedicalCenterController;
use App\Http\Controllers\API\AdminShopController;
use App\Http\Controllers\API\AidCenterController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\BreedController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CartItemController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\InteractController;
use App\Http\Controllers\API\MedicalCenterController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PetController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RatingDoctorController;
use App\Http\Controllers\API\RatingMedicalCenterController;
use App\Http\Controllers\API\RatingProductController;
use App\Http\Controllers\API\RatingShopController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\ShopDashboardController;
use App\Http\Controllers\API\SubOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Models\MedicalCenter;
use App\Models\RatingMedicalCenter;
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
  Route::post('/send-test-notification', [NotificationController::class, 'sendTestNotification']);
  Route::post('/login', [AuthController::class, 'login']);
  Route::post('/register-customer', [AuthController::class, 'registerCustomer']);
  Route::post('/register-shop', [AuthController::class, 'registerShop']);
  Route::post('/register-medical-center', [AuthController::class, 'registerMedicalCenter']);
  Route::post('/register-aid-center', [AuthController::class, 'registerAidCenter']);
  Route::post('/register-doctor', [AuthController::class, 'registerDoctor']);
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
  Route::get('/profile', [CustomerController::class, 'getProfile']);
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
  Route::post('/blogs', [BlogController::class, 'createBlog']);
  Route::get('/blogs/me/', [BlogController::class, 'getMyBlogs']);
  Route::get('/blogs/search/username', [BlogController::class, 'searchBlogByUsername']);
  Route::get('/blogs/search/title', [BlogController::class, 'searchBlogByTitle']);
  Route::get('/blogs/profile', [BlogController::class, 'searchProfile']);
  Route::get('/blogs/profile/{account_id}', [BlogController::class, 'showProfile']);
  Route::get('/blogs/{blog_id}', [BlogController::class, 'getBlogDetail']);
  Route::put('/blogs/{blog_id}', [BlogController::class, 'updateBlog']);
  Route::delete('/blogs/{blog_id}', [BlogController::class, 'deleteBlog']);

  // --------------     COMMENT     --------------
  Route::post('/comments', [CommentController::class, 'createComment']);
  Route::put('/comments/{comment_id}', [CommentController::class, 'updateComment']);
  Route::delete('/comments/{comment_id}', [CommentController::class, 'deleteComment']);

  // --------------     INTERACT     --------------
  Route::post('/interacts/blog/{blog_id}', [InteractController::class, 'interactBlog']);
  Route::post('/interacts/comment/{comment_id}', [InteractController::class, 'interactComment']);

  // --------------     RATING     --------------
  Route::get('/ratings/product/{product_id}', [RatingProductController::class, 'getCustomerRatingsOfProductId']); // lấy dsach rating của customer theo product id
  Route::get('/ratings/shop/{shop_id}', [RatingShopController::class, 'getCustomerRatingsOfShopId']); // lấy dsach rating của customer theo shop id
  Route::get('/ratings/doctor/{doctor_id}', [RatingDoctorController::class, 'getCustomerRatingsOfDoctorId']); // lấy dsach rating của customer theo doctor id
  Route::get('/ratings/medical-center/{medical_center_id}', [RatingMedicalCenterController::class, 'getCustomerRatingsOfMedicalCenterId']); // lấy dsach rating của customer theo medical center id

  Route::get('/ratings/product/{product_id}/detail', [RatingProductController::class, 'getDetailRatingProductOfCustomer']);
  Route::post('/ratings/product/{product_id}', [RatingProductController::class, 'createRatingProduct']);
  Route::patch('/ratings/{rating_id}/product', [RatingProductController::class, 'updateRatingProduct']);
  Route::delete('/ratings/{rating_id}/product', [RatingProductController::class, 'deleteRatingProduct']);

  Route::get('/ratings/shop/{shop_id}/detail', [RatingShopController::class, 'getDetailRatingShopOfCustomer']);
  Route::post('/ratings/shop/{shop_id}', [RatingShopController::class, 'createRatingShop']);
  Route::patch('/ratings/{rating_id}/shop', [RatingShopController::class, 'updateRatingShop']);
  Route::delete('/ratings/{rating_id}/shop', [RatingShopController::class, 'deleteRatingShop']);

  Route::get('/ratings/doctor/{doctor_id}/detail', [RatingDoctorController::class, 'getDetailRatingDoctorOfCustomer']);
  Route::post('/ratings/doctor/{doctor_id}', [RatingDoctorController::class, 'createRatingDoctor']);
  Route::patch('/ratings/{rating_id}/doctor', [RatingDoctorController::class, 'updateRatingDoctor']);
  Route::delete('/ratings/{rating_id}/doctor', [RatingDoctorController::class, 'deleteRatingDoctor']);
  
  Route::get('/ratings/medical-center/{medical_center_id}/detail', [RatingMedicalCenterController::class, 'getDetailRatingMedicalCenterOfCustomer']);
  Route::post('/ratings/medical-center/{medical_center_id}', [RatingMedicalCenterController::class, 'createRatingMedicalCenter']);
  Route::patch('/ratings/{rating_id}/medical-center', [RatingMedicalCenterController::class, 'updateRatingMedicalCenter']);
  Route::delete('/ratings/{rating_id}/medical-center', [RatingMedicalCenterController::class, 'deleteRatingMedicalCenter']);

  // --------------     AID CENTER     --------------
  Route::get('/adopt-request', [AidCenterController::class, 'getAdoptedRequest']);
  Route::get('/adopt-request/{adopt_request_id}', [AidCenterController::class, 'getDetailAdoptedRequest']);
  Route::get('/unadopted-pets-of-aid-center/{aid_center_id}', [AidCenterController::class, 'getUnadoptedPetsOfAidCenter']);
  Route::get('/unadopted-pets', [AidCenterController::class, 'getUnadoptedPets']);
  Route::get('/unadopted-pets/{pet_id}', [AidCenterController::class, 'getDetailUnadpotedPet']);
  Route::get('/my-adopted-pets', [AidCenterController::class, 'getMyAdoptedPets']);
  Route::get('/my-adopted-pets/{pet_id}', [AidCenterController::class, 'getDetailMyAdoptedPets']);
  Route::post('/adopt-pet/{pet_id}', [AidCenterController::class, 'adoptPet']);

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

  // --------------     SUB ORDER     --------------
  Route::get('/sub-orders/done', [SubOrderController::class, 'getDoneSubOrder']);

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
  Route::get('/profile/address', [ShopController::class, 'getAddress']);
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

// Medical Center API
Route::group([
  'middleware' => ['force.json.response', 'api', 'auth.user', 'auth.medical_center'],
  'prefix' => 'medical-center',
], function ($router) {
  // --------------     APPOINTMENT     --------------
  Route::get('/appointments/done', [AppointmentController::class, 'getListDoneAppointments']);
  Route::get('/appointments/waiting', [AppointmentController::class, 'getListWaitingAppointments']);

});


// Admin API
Route::group([
  'middleware' => ['force.json.response', 'api', 'auth.user', 'auth.admin'],
  'prefix' => 'admin',
], function ($router) {
  // --------------     DASHBOARD     --------------
  Route::get('/bar/shop', [AdminDashboardController::class, 'getShop']);
  Route::get('/bar/medical-center', [AdminDashboardController::class, 'getMedicalCenter']);
  Route::get('/bar/aid-center', [AdminDashboardController::class, 'getAidCenter']);
  Route::get('/bar/customer', [AdminDashboardController::class, 'getCustomer']);
  Route::get('/pie/account-type', [AdminDashboardController::class, 'getAccountType']);
  Route::get('/pie/account-status', [AdminDashboardController::class, 'getAccountStatus']);
  Route::get('/radar/account-approved', [AdminDashboardController::class, 'getAccountByApproved']);
  Route::get('/recent-waiting-approved-account', [AdminDashboardController::class, 'getRecentWaitingApprovedAccount']);

  Route::get('/recent-orders', [ShopDashboardController::class, 'getRecentOrder']);
  Route::get('/popular-products', [ShopDashboardController::class, 'getPopularProduct']);

  // --------------     SHOP     --------------
  Route::get('/shops', [AdminShopController::class, 'getShops']);
  Route::get('/shops/waiting-approve', [AdminShopController::class, 'getShopsWaitingApprove']);
  Route::get('/shops/blocked', [AdminShopController::class, 'getShopsBlocked']);
  Route::get('/shops/revenue/{shop_id}', [AdminShopController::class, 'getRevenueByShop']);
  Route::get('/shops/rating/{shop_id}', [AdminShopController::class, 'getRatingByShop']);
  Route::patch('/shops/approve/{account_id}', [AdminShopController::class, 'approveShop']);
  Route::patch('/shops/block/{account_id}', [AdminShopController::class, 'blockShop']);
  Route::patch('/shops/restore/{account_id}', [AdminShopController::class, 'restoreShop']);
  Route::get('/shops/{shop_id}', [AdminShopController::class, 'getShopDetail']);

  // --------------     MEDICAL CENTER     --------------
  Route::get('/medical-centers', [AdminMedicalCenterController::class, 'getMedicalCenters']);
  Route::get('/medical-centers/waiting-approve', [AdminMedicalCenterController::class, 'getMedicalCentersWaitingApproved']);
  Route::get('/medical-centers/blocked', [AdminMedicalCenterController::class, 'getMedicalCentersBlocked']);
  Route::get('/medical-centers/{medical_center_id}', [AdminMedicalCenterController::class, 'getMedicalCenterDetail']);
  Route::get('/medical-centers/rating/{medical_center_id}', [AdminMedicalCenterController::class, 'getRatingByMedicalCenter']);
  Route::patch('/medical-centers/approve/{account_id}', [AdminMedicalCenterController::class, 'approveMedicalCenter']);
  Route::patch('/medical-centers/block/{account_id}', [AdminMedicalCenterController::class, 'blockMedicalCenter']);
  Route::patch('/medical-centers/restore/{account_id}', [AdminMedicalCenterController::class, 'restoreMedicalCenter']);
  Route::get('/medical-centers/{medical_center_id}', [AdminMedicalCenterController::class, 'getMedicalCenterDetail']);

  // --------------     AID CENTER     --------------
  Route::get('/aid-centers', [AdminAidCenterController::class, 'getAidCenters']);
  Route::get('/aid-centers/waiting-approve', [AdminAidCenterController::class, 'getAidCentersWaitingApproved']);
  Route::get('/aid-centers/blocked', [AdminAidCenterController::class, 'getAidCentersBlocked']);
  Route::get('/aid-centers/{aid_center_id}', [AdminAidCenterController::class, 'getAidCenterDetail']);
  Route::patch('/aid-centers/approve/{account_id}', [AdminAidCenterController::class, 'approvedAidCenter']);
  Route::patch('/aid-centers/block/{account_id}', [AdminAidCenterController::class, 'blockAidCenter']);
  Route::patch('/aid-centers/restore/{account_id}', [AdminAidCenterController::class, 'restoreAidCenter']);

  // --------------     RATING DETAIL     --------------
  Route::get('/ratings/shop/{shop_id}/detail', [RatingShopController::class, 'getDetailRating']);
  Route::get('/ratings/medical-centers/{medical_center_id}/detail', [RatingMedicalCenterController::class, 'getDetailRating']);
  Route::get('/ratings/aid-centers/{aid_center_id}/detail', [RatingShopController::class, 'getDetailRating']);
});

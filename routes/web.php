<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandProduct;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryProduct;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ShippingFeeController;
use App\Http\Controllers\StatistiController;
use App\Http\Controllers\VNPayController;

//frontend
Route::get('/', [HomeController::class, 'index']);
Route::get('/trang-chu', [HomeController::class, 'index']);
Route::post('/tim-kiem', [HomeController::class, 'search']);


// danh muc san pham
Route::get('/danh-muc-san-pham/{category_id}', [CategoryProduct::class, 'show_category']);
Route::get('/thuong-hieu-san-pham/{brand_id}', [BrandProduct::class, 'show_brand']);
Route::get('/chi-tiet-san-pham/{product_id}', [ProductController::class, 'details_product']);



// //backend
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
Route::get('/logout', [AdminController::class, 'logout']);
Route::post('/admin-dashboard', [AdminController::class, 'dashboard']);


//employee 
Route::get('/employees', [EmployeeController::class, 'list_employee']);
Route::get('/employees-create', [EmployeeController::class, 'store_create']); // Trang tạo nhân viên
Route::post('/employees-store', [EmployeeController::class, 'store']); // Xử lý form tạo nhân viên
Route::post('/employees/lock/{id}', [EmployeeController::class, 'lock']);
Route::post('/employees/unlock/{id}', [EmployeeController::class, 'unlock']);
Route::delete('/employees-destroy/{id}', [EmployeeController::class, 'destroy']);
Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit_employee']);
Route::post('/employees-update/{id}', [EmployeeController::class, 'update']);


//tai khoan nguoi dung
Route::get('/customers-list', [AdminController::class, 'listCustomers']);
Route::post('/customers/lock/{id}', [AdminController::class, 'lock_customer']);
Route::post('/customers/unlock/{id}', [AdminController::class, 'unlock_customer']);
Route::post('/customers/delete/{id}', [AdminController::class, 'delete_customer']);

//category Product
Route::get('/add-Category-Product', [CategoryProduct::class, 'add_Category_Product']);
Route::get('/edit-category-product/{id}', [CategoryProduct::class, 'edit_Category_Product']);
Route::get('/delete-category-product/{id}', [CategoryProduct::class, 'delete_Category_Product']);
Route::post('/update-category-product/{id}', [CategoryProduct::class, 'update_Category_Product']);
Route::get('/all-Category-Product', [CategoryProduct::class, 'all_Category_Product']);
Route::post('/save-category-product', [CategoryProduct::class, 'save_Category_Product']);
Route::get('/active-category-product/{id}', [CategoryProduct::class, 'active_Category_Product']);
Route::get('/unActive-category-product/{id}', [CategoryProduct::class, 'unActive_Category_Product']);

//brand product
Route::get('/add-brand-Product', [BrandProduct::class, 'add_Brand_Product']);
Route::get('/edit-brand-product/{id}', [BrandProduct::class, 'edit_Brand_Product']);
Route::get('/delete-brand-product/{id}', [BrandProduct::class, 'delete_Brand_Product']);
Route::post('/update-brand-product/{id}', [BrandProduct::class, 'update_Brand_Product']);
Route::get('/all-brand-Product', [BrandProduct::class, 'all_Brand_Product']);
Route::post('/save-brand-product', [BrandProduct::class, 'save_Brand_Product']);
Route::get('/active-brand-product/{id}', [BrandProduct::class, 'active_Brand_Product']);
Route::get('/unActive-brand-product/{id}', [BrandProduct::class, 'unActive_Brand_Product']);


//product
Route::get('/add-Product', [ProductController::class, 'add_Product']);
Route::post('/save-Product', [ProductController::class, 'save_Product']);
Route::get('/all-Product', [ProductController::class, 'all_Product']);
Route::get('/edit-product/{id}', [ProductController::class, 'edit_Product']);
Route::post('/update-product/{id}', [ProductController::class, 'update_Product']);
Route::get('/delete-product/{id}', [ProductController::class, 'delete_Product']);
Route::get('/active-product/{id}', [ProductController::class, 'active_Product']);
Route::get('/unActive-product/{id}', [ProductController::class, 'unActive_Product']);

//cart
Route::post('/save-cart', [CartController::class, 'save_cart']);
Route::get('/show-cart', [CartController::class, 'show_cart']);
Route::get('/remove-cart/{product_id}', [CartController::class, 'remove_cart']);
Route::post('/update-cart', [CartController::class, 'update_cart']);
Route::get('/clear-cart', [CartController::class, 'clear_cart']);
Route::get('/delete-cart/{productId}', [CartController::class, 'delete_cart']);


//checkout customer
Route::get('/customer/login', [CustomerController::class, 'showLogin']);
Route::get('/customer/register', [CustomerController::class, 'showRegister']);
Route::post('/customer-login', [CustomerController::class, 'login']);
Route::post('/customer-register', [CustomerController::class, 'register']);
Route::get('/customer/logout', [CustomerController::class, 'logout']);
Route::get('/checkout', [CustomerController::class, 'check_out']);

//payment
// Route::post('/order-place', [OrderController::class, 'order_place']);
Route::get('/payment-card', [OrderController::class, 'paymentCard']);
Route::get('/payment-cash', [OrderController::class, 'paymentCash']);
Route::get('/thank-you', [OrderController::class, 'thank_you']);
//history order
Route::get('/order-history', [OrderController::class, 'orderHistory']);
Route::get('/order-detail/{order_id}', [OrderController::class, 'orderDetail']);

//shipping
Route::post('/save-shipping', [CustomerController::class, 'saveShipping']);
Route::get('/payment', [CustomerController::class, 'payment']);
Route::get('shipping-fees', [ShippingFeeController::class, 'index'])->name('shipping_fees.index');
Route::get('shipping-fees/create', [ShippingFeeController::class, 'create'])->name('shipping_fees.create');
Route::post('shipping-fees', [ShippingFeeController::class, 'store'])->name('shipping_fees.store');
Route::get('shipping-fees/{id}/edit', [ShippingFeeController::class, 'edit'])->name('shipping_fees.edit');
Route::put('shipping-fees/{id}', [ShippingFeeController::class, 'update'])->name('shipping_fees.update');
Route::delete('shipping-fees/{id}', [ShippingFeeController::class, 'destroy'])->name('shipping_fees.destroy');

Route::get('shipping-fees/get-districts/{matp}', [ShippingFeeController::class, 'getDistricts'])->name('shipping_fees.get_districts');
Route::get('shipping-fees/get-fee/{matp}/{maqh?}', [ShippingFeeController::class, 'getShippingFee'])->name('shipping_fees.get_fee');
Route::get('/edit-shipping', [ShippingFeeController::class, 'editShipping'])->name('frontend.edit_shipping');
Route::post('/update-shipping', [ShippingFeeController::class, 'updateShipping'])->name('frontend.update_shipping');
//
Route::get('/manage-order', [OrderController::class, 'manage_order']);
Route::get('/view-order/{orderId}', [OrderController::class, 'view_order']);
Route::post('/update-order-status/{orderId}', [OrderController::class, 'updateOrderStatus']);
Route::get('/delete-order/{orderId}', [OrderController::class, 'deleteOrder']);
Route::get('/admin/orders', [OrderController::class, 'listOrders']);


//in hoa đơn
Route::get('/print-invoice/{orderId}', [OrderController::class, 'print_invoice']);
//đánh giá sản phẩm
Route::post('/review/{product_id}', [CommentController::class, 'store'])->name('review.store');
Route::get('/reviews', [CommentController::class, 'index'])->name('admin.reviews.index');
Route::patch('/reviews/{id}', [CommentController::class, 'updateStatus'])->name('admin.reviews.update');
Route::delete('/reviews/{id}', [CommentController::class, 'destroy'])->name('admin.reviews.destroy');
//password reset
Route::get('/forgot-password', [CustomerController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [CustomerController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password', [CustomerController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [CustomerController::class, 'resetPassword'])->name('password.update');


//khuyen mai
Route::post('/apply-promotion', [PromotionController::class, 'applyPromotion'])->name('apply-promotion');
Route::get('/clear-promotion', [PromotionController::class, 'clearPromotion'])->name('clear-promotion');
Route::get('/promotions', [PromotionController::class, 'index'])->name('admin.promotions.index');
Route::get('/promotions/create', [PromotionController::class, 'create'])->name('admin.promotions.create');
Route::post('/promotions', [PromotionController::class, 'store'])->name('admin.promotions.store');
Route::get('/promotions/{id}/edit', [PromotionController::class, 'edit'])->name('admin.promotions.edit');
Route::put('/promotions/{id}', [PromotionController::class, 'update'])->name('admin.promotions.update');
Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('admin.promotions.destroy');

//
Route::get('/statistics', [StatistiController::class, 'index'])->name('statistics');
//vnpay
Route::post('/order-place', [CheckoutController::class, 'order_place'])->name('order.place');

Route::get('/vnpay/callback', [VNPayController::class, 'callback'])->name('vnpay.callback');
Route::get('/checkout/result', function () {
    return view('checkout_result');
})->name('checkout.result');
// Routes cho chatbox
Route::post('/chat/send', [ChatController::class, 'sendMessage']);
Route::get('/chat/history', [ChatController::class, 'getHistory']);
Route::get('/session-id', [ChatController::class, 'getSessionId']);

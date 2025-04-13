<?php

use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\admin\brandController;
use App\Http\Controllers\admin\categoryCotroller;
use App\Http\Controllers\admin\discountController;
use App\Http\Controllers\admin\homeController;
use App\Http\Controllers\admin\imageController;
use App\Http\Controllers\admin\orderController;
use App\Http\Controllers\admin\productController;
use App\Http\Controllers\admin\shoppingCharge;
use App\Http\Controllers\admin\subCategoryController;
use App\Http\Controllers\basicController;
use App\Http\Controllers\front\authController;
use App\Http\Controllers\front\homeController as FrontHomeController;
use App\Http\Controllers\front\shopController;
use App\Http\Controllers\front\shoppingController;
use App\Http\Controllers\paymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontHomeController::class, 'index'])->name('home');
Route::get('/shop/{category?}/{subCategory?}', [shoppingController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [shoppingController::class, 'product'])->name('product');
Route::get('/getProduct', [productController::class, 'productRelated'])->name('product.related');
Route::get('/cart', [shopController::class, 'cart'])->name('product.cart');
Route::post('/add-to-cart', [shopController::class, 'addToCart'])->name('product.addToCart');
Route::get('/cart-items', [shopController::class, 'cart'])->name('cartMenu');
Route::post('/cart-items', [shopController::class, 'cartItems'])->name('cartItems');
Route::post('/cart/update', [shopController::class, 'cartUpdate'])->name('cartUpdate');
Route::post('/cart/delete', [shopController::class, 'cartDelete'])->name('cartDelete');
Route::get('/checkout', [shopController::class, 'checkOut'])->name('checkOut');
Route::post('/checkout', [shopController::class, 'checkOutStore'])->name('user-checkout-store');
Route::get('/thank-you', [shopController::class, 'thankyou'])->name('user-thank');
Route::get('/country-change', [shopController::class, 'countryChange'])->name('countryChange');



Route::group(['prefix' => '/account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [authController::class, 'login'])->name('userLogin');
        Route::post('/login', [authController::class, 'loginAction'])->name('user-loginAction');
        Route::get('/register', [authController::class, 'register'])->name('userRegister');
        Route::post('/register', [authController::class, 'store'])->name('user-register-store');
    });
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [authController::class, 'profile'])->name('user-profile');
        Route::get('/logout', [authController::class, 'logout'])->name('user-logout');
        Route::post('/apply-coupon', [shopController::class, 'couponStore'])->name('apply-coupon');
        Route::get('/my-orders', [shopController::class, 'orders'])->name('my-order');
        Route::get('/order-items/{id}', [shopController::class, 'ordersItems'])->name('order-detail');
    });
});

Route::group(['prefix' => '/basic'], function () {
    // Route::group(['middleware' => ''],function(){
    Route::get('/register', [basicController::class, 'create'])->name('create');
    Route::post('/register', [basicController::class, 'store'])->name('store');
    Route::get('/show', [basicController::class, 'show'])->name('show');
    Route::get('/delete/{id}', [basicController::class, 'delete'])->name('delete');
    Route::get('/edit/{id}', [basicController::class, 'edit'])->name('edit');
    Route::get('/deleteimage', [basicController::class, 'deleteImg'])->name('deleteImage');
    Route::post('/update', [basicController::class, 'update'])->name('update');
    // });
});



Route::group(['prefix' => '/admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [adminController::class, 'index'])->name('admin-login');
        Route::post('/authenticate', [adminController::class, 'authenticate'])->name('admin-authenticate');
    });
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [homeController::class, 'index'])->name('admin-dashboard');
        Route::get('/logout', [homeController::class, 'logout'])->name('admin-logout');

        //category routes
        Route::get('/category/create', [categoryCotroller::class, 'create'])->name('admin-category-create');
        Route::post('/category/store', [categoryCotroller::class, 'store'])->name('admin-category-store');
        Route::get('/get-slug', [categoryCotroller::class, 'slug'])->name('get-slug');
        Route::get('/category', [categoryCotroller::class, 'index'])->name('category-list');
        Route::post('/image-temp-create', [imageController::class, 'index'])->name('temp-image-create');
        Route::post('/singleimage-temp-create', [imageController::class, 'sinleIndex'])->name('single-temp-image-create');
        Route::get('/category/edit/{id}', [categoryCotroller::class, 'edit'])->name('edit-category');
        Route::post('/category/update', [categoryCotroller::class, 'update'])->name('update-category');
        Route::get('/category/delete/{id}', [categoryCotroller::class, 'destroy'])->name('delete-category');

        //sub-categpory routes
        Route::get('/sub-category/create', [subCategoryController::class, 'create'])->name('admin-sub-category-create');
        Route::post('/sub-category/store', [subCategoryController::class, 'store'])->name('admin-sub-category-store');
        Route::get('/sub-category', [subCategoryController::class, 'index'])->name('sub-category-list');
        Route::get('/sub-category/delete/{id}', [subCategoryController::class, 'destroy'])->name('sub-category-delete-category');
        Route::get('/sub-category/edit/{id}', [subCategoryController::class, 'edit'])->name('edit-sub-category');
        Route::post('/sub-category/update', [subCategoryController::class, 'update'])->name('admin-sub-category-update');

        //brands routes
        Route::get('/brands/create', [brandController::class, 'create'])->name('admin-brand-create');
        Route::post('/brands/store', [brandController::class, 'store'])->name('admin-brand-store');
        Route::get('/brands', [brandController::class, 'index'])->name('admin-brand-list');
        Route::get('/brand/delete/{id}', [brandController::class, 'destroy'])->name('admin-brand-delete');
        Route::get('/brand/edit/{id}', [brandController::class, 'edit'])->name('admin-brand-edit');
        Route::post('/brand/upadate/', [brandController::class, 'update'])->name('update-brand');

        //products route
        Route::get('/product/sub_category', [productController::class, 'subCategory'])->name('product-subCategory');
        Route::get('/product/create', [productController::class, 'create'])->name('admin-product-create');
        Route::post('/product/store', [productController::class, 'store'])->name('admin-product-store');
        Route::get('/products', [productController::class, 'index'])->name('admin-product-list');
        Route::get('/product/delete/{id}', [productController::class, 'destroy'])->name('admin-product-delete');
        Route::get('/product/edit/{id}', [productController::class, 'edit'])->name('admin-product-edit');
        Route::post('/product/update/', [productController::class, 'update'])->name('admin-product-update');
        Route::post('/product/update/image', [productController::class, 'updateImage'])->name('update-productImage');
        Route::get('/product/image-delete', [productController::class, 'deleteProductImage'])->name('deleteProductImage');

        //shipping routes
        Route::group(['prefix' => '/shipping'], function () {
            Route::get('/create', [shoppingCharge::class, 'index'])->name('shipping-index');
            Route::post('/store', [shoppingCharge::class, 'store'])->name('shipping-store');
            Route::get('/edit/{id}', [shoppingCharge::class, 'edit'])->name('shipping-edit');
            Route::post('/update', [shoppingCharge::class, 'update'])->name('shipping-update');
            Route::get('/delete/{id}', [shoppingCharge::class, 'destroy'])->name('shipping-delete');
            Route::post('/shippping/update', [shoppingCharge::class, 'otherShipUpdate'])->name('shipping-other-update');
        });

        //discounts routes
        Route::group(['prefix' => '/discount'], function () {
            Route::get('/create', [discountController::class, 'create'])->name('discount-create');
            Route::post('/store', [discountController::class, 'store'])->name('discount-store');
            Route::get('/list', [discountController::class, 'index'])->name('discount-index');
            Route::get('/edit/{id}', [discountController::class, 'edit'])->name('discount-edit');
            Route::post('/update', [discountController::class, 'update'])->name('discount-update');
            Route::get('/delete/{id}', [discountController::class, 'delete'])->name('discount-delete');
            Route::get('/coupon-remove', [shopController::class, 'couponRemoved'])->name('remove-coupon');
        });

        //orders routes
        Route::get('/orders', [orderController::class, 'orders'])->name('admin-order-list');
        Route::get('/orders-detail/{id}', [orderController::class, 'orderDetail'])->name('admin-order-detail');
        Route::post('/status-update', [orderController::class, 'statusUpdate'])->name('status-update');
    });
});
Route::get('/stripe', [paymentController::class, 'index'])->name('payment-index');
Route::post('/stripe', [paymentController::class, 'catch'])->name('payment-catch');
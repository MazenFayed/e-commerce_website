<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\VerifyMobileController;
use App\Http\Controllers\EmailDashboard;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProductListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\ContactUsController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//user routes
Route::get('/', [UserController::class, 'index'])->name('user.home');

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');  // middleware(['auth', 'verified']) FOR email verification

Route::get('/emaildashboard', [EmailDashboard::class,'index'])->middleware(['auth', 'verified'])->name('emaildashboard');  // middleware(['auth', 'verified']) FOR email verification

Route::get('verify-mobile', [VerifyMobileController::class, 'send_sms'])->name('send.sms');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //checkout
    Route::middleware(['auth', 'verified'])->prefix('checkout')->controller(CheckoutController::class)->group(function () {
        Route::post('order','store')->name('checkout.store');
        Route::get('success','success')->name('checkout.success');
        Route::get('cancel','cancel')->name('checkout.cancel');
    });

}); 


//end

//admin routes   ,'middleware'=>'redirectAdmin'

Route::group(['prefix'=>'admin','middleware'=>'redirectAdmin', 'verified'],function()
{
    Route::get('/profile', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/profile', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/profile', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth','admin', 'verified'])->prefix('admin')->group(function()
{
    Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard');

    //product routes
   //## Route::get('/products',[ProductController::class,'index'])->name('admin.products.index');
    Route::post('/products/store',[ProductController::class,'store'])->name('admin.products.store');
    Route::post('/products/update',[ProductController::class,'update'])->name('admin.products.update');
    Route::delete('/products/image/{id}',[ProductController::class,'deleteImage'])->name('admin.products.image.delete');
    Route::delete('/products/delete/{id}',[ProductController::class,'deleteproduct'])->name('admin.products.product.delete');

});

Route::prefix('cart')->controller(CartController::class)->group(function(){
    Route::get('view','view')->name('cart.view');
    Route::post('store/{product}','store')->name('cart.store');
    Route::patch('update/{product}','update')->name('cart.update');
    Route::delete('delete/{product}','delete')->name('cart.delete');

});

//routes for products and filters
/*Route::middleware(['auth', 'verified'])->prefix('products')->controller(ProductListController::class)->group(function(){
    Route::get('/','index')->name('products.index');
});*/

Route::controller(ProductListController::class)->group(function(){
    Route::get('admin/products','product_search')->name('products.search');
});
//paypal cart payment
Route::middleware(['auth', 'verified'])->group(function(){
Route::post('paypal',[PayPalController::class,'payment'])->name('payment');
Route::get('paypal/success',[PayPalController::class,'success'])->name('success');
Route::get('paypal/cancel',[PayPalController::class,'cancel'])->name('cancel');
});
//ask to be admin
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/AskToBeAdmin', [AdminController::class,'show'])->name('asktobeadmin');
});

//contact us routes
Route::controller(ContactUsController::class)->group(function(){
    Route::get('contactus','index')->name('contactus.view');
});

//instagram route
Route::middleware(['auth', 'verified'])->group(function(){
Route::get('/instagram', [ReportController::class, 'getinstagrampagesinfo'])->name('instagram.data');
Route::get('/facebook', [ReportController::class, 'getfacebookpagesinfo'])->name('facebook.data');
Route::get('/user_name', [ReportController::class, 'index'])->name('choose_report');
Route::get('/user_name/set', [ReportController::class, 'username'])->name('Pageusername');
Route::get('/instagramreport', [ReportController::class, 'getinstagramreport'])->name('instagram.report');
Route::get('/twitter', [ReportController::class, 'gettwitterpagesinfo'])->name('twitter');

//Route::get('/instagram')->name('getlink');
});

require __DIR__.'/auth.php';

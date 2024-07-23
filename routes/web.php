<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

/*use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/redirect', [HomeController::class, 'redirect']);
Route::get('/', [HomeController::class, 'index']);
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::post('/addcart/{id}', [HomeController::class, 'addcart']);
Route::get('/showcart', [HomeController::class, 'showcart']);
Route::get('/delete/{id}', [HomeController::class, 'deletecart']);
Route::post('/order', [HomeController::class, 'confirmorder']);
Route::get('/aboutus', [HomeController::class, 'aboutus']);
Route::get('/ourproducts', [HomeController::class, 'ourproducts']);
Route::get('/contactus', [HomeController::class, 'contactus']);
Route::get('/payment-gateway', [PaymentController::class, 'show'])->name('payment.gateway');
// Route::resource('orders', OrderController::class)->only(['index', 'show']);
Route::get('/payment/show', [PaymentController::class, 'show'])->name('payment.show');
// Route::post('/submit-order', [OrderController::class, 'submitOrder']);

Route::get('/payment-success', function () {
    return view('payment.payment_success');
});

Route::post('/update-payment-status', 'PaymentController@updatePaymentStatus')->name('update.payment.status');


Route::post('/update-payment-status', [PaymentController::class, 'updatePaymentStatus']);

Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

Route::post('/payment-success', [HomeController::class, 'paymentSuccess'])->name('payment.success');

Route::get('/payment/show', [PaymentController::class, 'show'])->name('payment.show');


// Endpoint untuk menampilkan halaman sukses pembayaran
Route::get('/payment-success', function () {
    return view('payment.payment_success');
})->name('payment.success.page');


// Route::post('/clear-cart', 'CartController@clearCartAfterPayment')->name('clear.cart.after.payment');

// Route::get('/clear-cart', [CartController::class, 'clearCartAfterPayment'])->name('clearCartAfterPayment');

// Route::get('/clear-cart', [CartController::class, 'clearCart'])->name('clear.cart');




Route::middleware(['auth', 'admin.rule'])->group(function () {
    Route::get('/product', [AdminController::class, 'product']);
    Route::post('/uploadproduct', [AdminController::class, 'uploadproduct']);
    Route::get('/showproduct', [AdminController::class, 'showproduct']);
    Route::get('/deleteproduct/{id}', [AdminController::class, 'deleteproduct']);
    Route::get('/updateview/{id}', [AdminController::class, 'updateview']);
    Route::post('/updateproduct/{id}', [AdminController::class, 'updateproduct']);
    Route::get('/orderadmin', [AdminController::class, 'showorder']);
    Route::get('/updatestatus/{id}', [AdminController::class, 'updatestatus']);
    Route::get('/adminDashboard', [AdminController::class, 'dashboardAdmin']);


});


//buat nampilin detail order di home
// Route::get('/showorder', [OrderController::class, 'showOrder'])->name('showorder');
// Route::get('/orders', [OrderController::class, 'showOrders'])->name('orders');
Route::get('/showorder', [HomeController::class, 'showorder']);
Route::get('/orders', [HomeController::class, 'showOrders'])->name('orders');
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\OrdersController;
use App\Http\Controllers\UserController;

// Auth routes
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// User management
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

// Product routes
Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::middleware(['auth', 'role:Admin|Employee'])->group(function () {
    Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
    Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
    Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
});

// Order routes
// Specific routes first to prevent route conflicts
Route::get('orders/confirmation/{order}', [OrdersController::class, 'confirmation'])->name('orders.confirmation');
Route::get('orders', [OrdersController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [OrdersController::class, 'show'])->name('orders.show');

// Customer specific routes
Route::middleware(['auth', 'role:Customer'])->group(function () {
    Route::post('cart/add/{product}', [OrdersController::class, 'addToCart'])->name('cart.add');
    Route::get('cart', [OrdersController::class, 'cart'])->name('cart');
    Route::delete('cart/remove/{productId}', [OrdersController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('checkout', [OrdersController::class, 'checkout'])->name('checkout');
    Route::post('place-order', [OrdersController::class, 'placeOrder'])->name('orders.place');
});

// Admin/Employee routes
Route::middleware(['auth', 'role:Admin|Employee'])->group(function () {
    Route::patch('orders/{order}/status', [OrdersController::class, 'updateStatus'])->name('orders.update.status');
    Route::get('customers/{user}/add-credits', [OrdersController::class, 'addCreditsForm'])->name('add_credits_form');
    Route::post('customers/{user}/add-credits', [OrdersController::class, 'addCredits'])->name('add_credits');

    // Customer management
    Route::get('customers', [UserController::class, 'customers'])->name('users.customers');
    Route::get('customers/{user}/credits', [UserController::class, 'showAddCredits'])->name('users.credits.show');
    Route::post('customers/{user}/credits', [UserController::class, 'addCredits'])->name('users.credits.add');
});

// User profile route
Route::get('user/profile/{user?}', [UserController::class, 'profile'])->name('user.profile');

// Admin routes
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('employees/create', [UserController::class, 'createEmployee'])->name('create_employee');
    Route::post('employees/store', [UserController::class, 'storeEmployee'])->name('store_employee');
});

// Basic pages
Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/transcript', function () {
    $transcript = [
        'Mathematics' => 'A',
        'Physics' => 'B+',
        'Chemistry' => 'A-',
        'Biology' => 'B',
        'Computer Science' => 'A+'
    ];
    return view('transcript', ['transcript' => $transcript]);
});

Route::get('/calculator', function () {
    return view('calculator');
});
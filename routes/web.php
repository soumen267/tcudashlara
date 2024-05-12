<?php

use App\Http\Controllers\CrmController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\SmtpController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home/create', [App\Http\Controllers\HomeController::class, 'create'])->name('home.create');
Route::post('/home/store', [App\Http\Controllers\HomeController::class, 'store'])->name('home.store');
Route::any('/dashboard/{id}', [App\Http\Controllers\HomeController::class, 'mainData'])->name('main');
Route::get('/create-account/{order_id?}', [App\Http\Controllers\DashboardController::class, 'accountCreate']);
Route::get('/create-customer', [App\Http\Controllers\DashboardController::class, 'createCustomer']);

Route::post('/webhook', 'WebHookController@index');

Route::resource('crm', CrmController::class);
Route::any('crm/{id}/{status}', [App\Http\Controllers\CrmController::class, 'changeStatus'])->name('crm.status');
Route::resource('smtp', SmtpController::class);
Route::any('smtp/{id}/{status}', [App\Http\Controllers\SmtpController::class, 'changeStatus'])->name('smtp.status');
Route::resource('shopify', ShopifyController::class);
Route::any('shopify/{id}/{status}', [App\Http\Controllers\ShopifyController::class, 'changeStatus'])->name('shopify.status');

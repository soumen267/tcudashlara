<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\DashboardController;

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

Route::controller(HomeController::class)->group(function(){
    Route::get('/home', 'index')->name('home');
    Route::get('/dashboard/{id}', 'mainData')->name('main');
    Route::get('/dashboard/failed/{id}', 'failedData');
    Route::post('/dashboard/getdata', 'getData');
    Route::post('/dashboard/getdashdata', 'getDashData')->name('getDashData');
    Route::put('/update', 'customerUpdate')->name('home.update');
    Route::post('checkOrder', 'orderCheck')->name('home.check');
    Route::post('searchOrder', 'orderSearch')->name('home.search');
});
Route::controller(DashboardController::class)->group(function(){
    Route::get('/dashboard', 'index')->name('dashboard.index');
    Route::get('/dashboards/create', 'create')->name('dashboards.create');
    Route::post('/dashboards/store', 'store')->name('dashboards.store');
    Route::any('/dashboards/edit/{id}', 'edit')->name('dashboards.edit');
    Route::put('/dashboards/update', 'dashUpdate');
    Route::any('/create-account/{order_id?}', 'accountCreate')->name('dashboard.create-account');
    Route::get('/create-customer', 'createCustomer');
    Route::get('updatePid', 'updatePIDNotRegData');
    Route::any('insertCRMS', 'shopifyToCrms');
});
Route::any('/send-email', [App\Http\Controllers\EmailController::class, 'sendEmail'])->name('sendEmail');

// Route::post('/webhook', 'WebHookController@index');

Route::resource('crm', CrmController::class);
Route::any('crm/{id}/{status}', [App\Http\Controllers\CrmController::class, 'changeStatus'])->name('crm.status');
Route::resource('smtp', SmtpController::class);
Route::any('smtp/{id}/{status}', [App\Http\Controllers\SmtpController::class, 'changeStatus'])->name('smtp.status');
Route::resource('shopify', ShopifyController::class);
Route::any('shopify/{id}/{status}', [App\Http\Controllers\ShopifyController::class, 'changeStatus'])->name('shopify.status');

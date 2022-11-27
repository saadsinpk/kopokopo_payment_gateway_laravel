<?php

use Illuminate\Support\Facades\Route;


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

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::any('urlback', [\App\Http\Controllers\OrderController::class, 'urlback'])->name('urlback');

Route::get('login/{service}', [\App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider']);
Route::get('login/{service}/callback', [\App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback']);
Route::get('firebase/gen','\App\Http\Controllers\AppSettingsController@generateFirebase');
Route::get('firebase-messaging-sw.js','\App\Http\Controllers\AppSettingsController@generateFirebase');
Route::get('settings/clear-cache',[\App\Http\Controllers\Admin\SettingsController::class,'clearCache'])->name('settings.clear_cache');

Auth::routes();

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/','App\Http\Controllers\Admin\DashboardController@index')->name('dashboard');
    Route::post('/dashboard/ajaxGetOrders','App\Http\Controllers\Admin\DashboardController@ajaxGetOrders')->name('dashboard.ajaxGetOrders');

    Route::get('customersJson', [\App\Http\Controllers\Admin\UserController::class,'indexJson'])->name('customersJson');
    Route::get('couriersJson', [\App\Http\Controllers\Admin\CourierController::class,'indexJson'])->name('couriersJson');

    Route::resource('couriers', \App\Http\Controllers\Admin\CourierController::class)->except(['create','store']);

    Route::get('settings/general', [\App\Http\Controllers\Admin\SettingsController::class,'general'])->name('settings.general');
    Route::get('settings/app', [\App\Http\Controllers\Admin\SettingsController::class,'app'])->name('settings.app');
    Route::get('settings/pricing', [\App\Http\Controllers\Admin\SettingsController::class,'pricing'])->name('settings.pricing');
    Route::get('settings/translations', [\App\Http\Controllers\Admin\SettingsController::class,'translations'])->name('settings.translations');
    Route::get('settings/currencies', [\App\Http\Controllers\Admin\SettingsController::class,'currencies'])->name('settings.currencies');
    Route::get('settings/social_login', [\App\Http\Controllers\Admin\SettingsController::class,'social_login'])->name('settings.social_login');
    Route::get('settings/payments_api', [\App\Http\Controllers\Admin\SettingsController::class,'payments_api'])->name('settings.payments_api');
    Route::get('settings/notifications', [\App\Http\Controllers\Admin\SettingsController::class,'notifications'])->name('settings.notifications');
    Route::get('settings/legal', [\App\Http\Controllers\Admin\SettingsController::class,'legal'])->name('settings.legal');
    Route::get('settings/currency', [\App\Http\Controllers\Admin\SettingsController::class,'currency'])->name('settings.currency');
    Route::resource('settings/currencies', App\Http\Controllers\Admin\CurrencyController::class);
    Route::resource('settings/roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('settings/offlinePaymentMethods', \App\Http\Controllers\Admin\OfflinePaymentMethodController::class);
    Route::get('settings/permissions', [\App\Http\Controllers\Admin\PermissionController::class,'index'])->name('permissions.index');
    Route::post('settings/permissions/update', [\App\Http\Controllers\Admin\PermissionController::class,'update'])->name('permissions.update');
    Route::patch('settings/saveSettings', [\App\Http\Controllers\Admin\SettingsController::class,'storeSettings'])->name('settings.saveSettings');
    Route::get('settings/clear-cache',[\App\Http\Controllers\Admin\SettingsController::class,'clearCache'])->name('settings.clear_cache');



    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('users/login_as/{id}',[App\Http\Controllers\Admin\UserController::class,'loginAs'])->name('users.login_as');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);


    Route::get('orders/ajaxGetAddressesHtml',[\App\Http\Controllers\Admin\OrderController::class, 'ajaxGetAddressesHtml'])->name('orders.ajaxGetAddressesHtml');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index','show','edit','update','destroy']);


    Route::get('courierPayouts/courierTable',[\App\Http\Controllers\Admin\CourierPayoutController::class,'getCourierPayoutDataTable'])->name('courierPayouts.courierTable');
    Route::get('courierPayouts/courierSummary',[\App\Http\Controllers\Admin\CourierPayoutController::class,'getCourierPayoutSummaryDataTable'])->name('courierPayouts.courierSummary');
    Route::resource('courierPayouts', \App\Http\Controllers\Admin\CourierPayoutController::class);


    Route::any('reports/ordersByDate',[\App\Http\Controllers\Admin\ReportController::class,'ordersByDate'])->name('reports.ordersByDate');
    Route::any('reports/ordersByDriver',[\App\Http\Controllers\Admin\ReportController::class,'ordersByDriver'])->name('reports.ordersByDriver');
    Route::any('reports/ordersByCustomer',[\App\Http\Controllers\Admin\ReportController::class,'ordersByCustomer'])->name('reports.ordersByCustomer');


    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

});

Route::middleware('auth')->group(function(){
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/ajaxGetAddressesHtml',[\App\Http\Controllers\OrderController::class, 'ajaxGetAddressesHtml'])->name('orders.ajaxGetAddressesHtml');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class,'show'])->name('orders.show');
    Route::post('/orders/{order}', [\App\Http\Controllers\OrderController::class,'show'])->name('orders.show')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

});

Route::get('logout',function(){
    auth()->logout();
    return redirect('/');
});

Route::get('terms',function(){
    return view('auth.terms');
})->name('terms');
Route::get('privacy',function(){
    return view('auth.privacy');
})->name('privacy');

Route::get('{slug}', [\App\Http\Controllers\CourierController::class, 'index'])->name('slug');

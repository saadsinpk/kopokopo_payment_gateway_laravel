<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SettingsAPIController;

use App\Http\Controllers\API\Courier\CourierAPIController;
use \App\Http\Controllers\API\OrderAPIController;
use \App\Http\Controllers\API\Courier\CourierOrderAPIController;
use App\Http\Controllers\API\MessageAPIController;
use \App\Http\Controllers\API\UserAPIController;
use \App\Http\Controllers\API\WebhookAPIController;
use \App\Http\Middleware\CourierAuth;
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

Route::any('webhook/stripe', 'WebhookAPIController@stripeWebhook');
Route::any('webhook/paypal', 'WebhookAPIController@paypalWebhook');
Route::any('webhook/mercadopago', 'WebhookAPIController@mercadoPagoWebhook');
Route::any('webhook/flutterwave', 'WebhookAPIController@flutterwaveWebhook');
Route::any('webhook/razorpay', 'WebhookAPIController@razorpayWebhook');
Route::any('webhook/kopokopo', 'WebhookAPIController@kopoKopoWebhook');


Route::get('settings', [SettingsAPIController::class, 'settings']);

Route::get('settings/categories', [SettingsAPIController::class, 'categories']);



Route::group(['prefix' => 'driver'], function () {
    Route::post('login', [CourierAPIController::class, 'login']);
    Route::post('register', [CourierAPIController::class, 'register']);
});

Route::post('login', [UserAPIController::class, 'login']);
Route::post('register', [UserAPIController::class, 'register']);

Route::post('login/check', [UserAPIController::class, 'loginCheck']);

Route::post('forgot-password', [UserAPIController::class, 'forgotPassword']);

Route::middleware('auth:api')->group(function () {

    Route::middleware(CourierAuth::class)->prefix('driver')->group(function () {
        Route::resource('orders', CourierOrderAPIController::class)->only([
            'index', 'show'
        ]);
        Route::get('login/verify', [CourierAPIController::class, 'verifyLogin']);
        Route::get('checkNewOrder', [CourierOrderAPIController::class, 'checkNewOrder']);
        Route::patch('updateStatus', [CourierOrderAPIController::class, 'updateStatus']);
        Route::get('getSummarizedBalance', [CourierOrderAPIController::class, 'getSummarizedBalance']);
    });

    //general routes that can be used by any role
    Route::get('login/verify', [UserAPIController::class, 'verifyLogin']);
    Route::post('profile', [UserAPIController::class, 'updateProfile']);
    Route::post('profile/picture', [UserAPIController::class, 'updateProfilePicture']);
    Route::delete('delete-account', [UserAPIController::class, 'deleteAccount']);


    Route::get('active', [CourierAPIController::class, 'getDeliveryActive']);
    Route::patch('active', [CourierAPIController::class, 'updateDeliveryActive']);
    Route::patch('settings', [CourierAPIController::class, 'updateSettings']);

    Route::patch('location', [CourierAPIController::class, 'updateLocation']);

    Route::post('driver/findNearBy', [CourierAPIController::class, 'findNearBy']);


    Route::post('orders/simulate', [OrderAPIController::class, 'simulate']);
    Route::resource('orders', OrderAPIController::class)->only([
        'index', 'show', 'store'
    ]);
    Route::post('orders/cancel', [OrderAPIController::class, 'cancel']);
    Route::post('orders/getStatus', [OrderAPIController::class, 'getStatus']);
    Route::post('orders/getCourierPosition', [OrderAPIController::class, 'getCourierPosition']);

    Route::get('orders/{id}/checkPaymentByOrderID', [OrderAPIController::class, 'checkPaymentByOrderID']);
    Route::get('orders/{id}/payWithMercadoPago', [OrderAPIController::class, 'payWithMercadoPago']);
    Route::get('orders/{id}/payWithPayPal', [OrderAPIController::class, 'payWithPayPal']);
    Route::get('orders/{id}/payWithFlutterwave', [OrderAPIController::class, 'payWithFlutterwave']);
    Route::get('orders/{id}/payWithRazorpay', [OrderAPIController::class, 'payWithRazorpay']);
    Route::post('orders/{order}/success', [OrderAPIController::class, 'paymentSuccessScreen'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('orders/initializePayment', [OrderAPIController::class, 'initializePayment']);

    Route::post('notifications/update_token', [UserAPIController::class, 'updateToken']);

    Route::resource('messages', MessageAPIController::class)->only([
        'index', 'store'
    ]);
});

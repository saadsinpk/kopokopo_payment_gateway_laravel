<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Illuminate\Support\Facades\Schema;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        Stripe::setApiKey(Config::get('services.stripe.secret'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        try {

            /*
             * Register mail settings from database to conf
             */
            config(['mail.driver' => setting('mail_driver', 'smtp')]);
            config(['mail.host' => setting('mail_host', 'smtp.mailgun.org')]);
            config(['mail.port' => setting('mail_port', 587)]);
            config(['mail.encryption' => setting('mail_encryption', 'tls')]);
            config(['mail.username' => setting('mail_username')]);
            config(['mail.password' => setting('mail_password')]);
            config(['mail.from.address' => setting('mail_from_address')]);
            config(['mail.from.name' => setting('mail_from_name')]);

            /*
             * Register social login settings from database to conf
             */
            config(['services.facebook.client_id' => setting('facebook_app_id')]);
            config(['services.facebook.client_secret' => setting('facebook_app_secret')]);
            config(['services.facebook.redirect' => url('login/facebook/callback')]);
            config(['services.twitter.client_id' => setting('twitter_app_id')]);
            config(['services.twitter.client_secret' => setting('twitter_app_secret')]);
            config(['services.twitter.redirect' => url('login/twitter/callback')]);
            config(['services.google.client_id' => setting('google_app_id')]);
            config(['services.google.client_secret' => setting('google_app_secret')]);
            config(['services.google.redirect' => url('login/google/callback')]);

            /*
             * Register Payment Gateways settings from database to conf
             */
            //stripe
            config(['services.stripe.key' => setting('stripe_key')]);
            config(['services.stripe.secret' => setting('stripe_secret')]);
            Stripe::setApiKey(setting('stripe_secret'));
            Stripe::setClientId(setting('stripe_key'));


            //FCM
            config(['services.fcm.key' => setting('firebase_serverkey', '')]);


            //paypal
            config(['paypal.mode' => setting('paypal_mode', '0') != '0' ? 'live' : 'sandbox']);
            config(['paypal.currency' => Str::upper(setting('currency', 'USD'))]);
            config(['paypal.username' => setting('paypal_username')]);
            config(['paypal.password' => setting('paypal_password')]);
            config(['paypal.secret' => setting('paypal_secret')]);
            config(['paypal.app_id' => setting('paypal_app_id')]);


            //mercadopago
            config(['mercadopago.app_id' => setting('mercadopago_app_id')]);
            config(['mercadopago.public_key' => setting('mercadopago_public_key')]);
            config(['mercadopago.access_token' => setting('mercadopago_access_token')]);
            config(['mercadopago.client_id' => setting('mercadopago_client_id')]);
            config(['mercadopago.client_secret' => setting('mercadopago_client_secret')]);

            /*
             * Timezone and locale settings from database to conf
             */
            config(['app.timezone' => setting('timezone','UTC')]);
            config(['app.locale' => setting('language', 'en')]);

        }catch (\Exception $e){
            //log the error
            \Log::error($e->getMessage());
        }
    }
}

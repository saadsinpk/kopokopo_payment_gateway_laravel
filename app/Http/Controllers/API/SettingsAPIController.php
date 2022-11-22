<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OfflinePaymentMethod;
use App\Repositories\UploadRepository;
use Illuminate\Support\Str;

class SettingsAPIController extends Controller
{

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    public function settings()
    {
        try {
            $offlinePayments = OfflinePaymentMethod::all();
            $settings = setting()->all();
            $settings['currency_symbol'] = getUtf8ConvertedStringFromHtmlEntities(getCurrencySymbolByCurrencyCode($settings['currency']??'USD'));
            $settings = array_intersect_key(
                $settings,
                [
                    'app_name' => '',
                    'enable_terms_of_service' => '',
                    'terms_of_service' => '',
                    'enable_privacy_policy' => '',
                    'privacy_policy' => '',
                    'currency' => '',
                    'currency_right' => '',
                    'currency_symbol' => '',
                    'distance_unit' => '',
                    'main_color' => '',
                    'secondary_color' => '',
                    'highlight_color' => '',
                    'background_color' => '',
                    'main_color_dark_theme' => '',
                    'secondary_color_dark_theme' => '',
                    'highlight_color_dark_theme' => '',
                    'background_color_dark_theme' => '',
                    'allow_custom_order_values' => '',
                    'enable_google' => '',
                    'enable_facebook' => '',
                    'enable_twitter' => '',
                    'google_maps_key' => '',
                    'header_text' => '',
                    'subheader_text' => '',
                    'enable_stripe' => '',
                    'stripe_key' => '',
                    'enable_paypal' => '',
                    'paypal_key' => '',
                    'enable_mercado_pago' => '',
                    'mercado_pago_key' => '',
                    'enable_flutterwave' => '',
                    'flutterwave_key' => '',
                    'enable_razorpay' => '',
                ]
            );
            $background_image = $this->uploadRepository->getByUuid(setting('background_image', ''));
            if ($background_image && $background_image->hasMedia('default')) {
                $settings['background_image'] = $background_image->getFirstMediaUrl('default');
            }
            $settings['offline_payment_methods'] = $offlinePayments;
            $locale = explode("-",app()->getLocale());
            $settings['locale'] = $locale[0] ?? 'en';
            $settings['locale_region'] = isset($locale[1]) ? Str::upper($locale[1]) : null;

            return $this->sendResponse($settings, 'Settings retrieved successfully');
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    public function categories()
    {
        try {
            $categories = Category::all();

            return $this->sendResponse($categories, 'Categories retrieved successfully');

        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
}

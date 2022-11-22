<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\Upload;
use App\Repositories\UploadRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;

class SettingsController extends AppBaseController
{

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    /*
     * Show general settings form page
     * @return Response
     */
    public function general(){
        return view('admin.settings.general');
    }

    /*
     * Show app settings form page
     * @return Response
     */
    public function app(){
        $background_image = $this->uploadRepository->getByUuid( setting('background_image', ''));
        if ($background_image && $background_image->hasMedia('default')) {
            $background_image = $background_image->getFirstMediaUrl('default');
        }
        return view('admin.settings.app_settings', compact('background_image'));
    }


    /*
     * Show social login settings form page
     * @return Response
     */
    public function social_login(){
        return view('admin.settings.social_login');
    }

    /*
     * Show social login settings form page
     * @return Response
     */
    public function payments_api(){
        return view('admin.settings.payments_api');
    }

    /*
     * Show mail settings form page
     * @return Response
     */
    public function notifications(){
        return view('admin.settings.notifications');
    }

    /*
     * Show legal settings form page
     * @return Response
     */
    public function legal(){
        return view('admin.settings.legal');
    }

    /*
     * Show pricing settings form page
     * @return Response
     */
    public function pricing(){
        return view('admin.settings.pricing');
    }

    /*
     * Show currency settings form page
     * @return Response
     */
    public function currency(){
        $currencies = array();
        foreach(getAvailableCurrencies() as $availableCurrency){
            $currencies[$availableCurrency['code']] = $availableCurrency['name'].' ('.$availableCurrency['symbol'].')';
        }
        return view('admin.settings.currency',[
            'currencies' => $currencies
        ]);
    }

    /*
     * Clear cache of application
     * @return Redirect
     */
    public function clearCache(Request $request)
    {
        Artisan::call('optimize:clear');
        if(Auth::check()){
            Flash::success(__('Cache successfully cleared!'));
            return redirect()->back();
        }else{
            return redirect()->route('home');
        }

    }


    /*
     * Store/Update general settings
     * @param Request $request
     * @return Redirect
     */
    public function storeSettings(Request $request){
        if (!env('APP_DEMO', false)) {
            $previousUrl = url()->previous();
            $settingsToSave = array();
            if(Str::endsWith($previousUrl,'settings/general')) {
                //update general settings

                $this->validate($request, [
                    'app_name' => 'required|max:250',
                    'logo' => 'nullable|mimes:jpg,png,gif,webp'
                ]);

                if ($request->hasFile('app_logo')) {
                    try {
                        $uuid = Str::uuid();
                        $logo = $request->file('app_logo');

                        $upload = Upload::create([
                            'uuid' => $uuid,
                        ]);
                        $upload->addMedia($logo)->toMediaCollection('default');
                        $settingsToSave += [
                            'app_logo' => $uuid
                        ];
                    } catch (\Exception $e) {
                        report($e);
                        Flash::error(__('Error occurred while uploading logo'));
                        return redirect()->back();
                    }
                }

                $settingsToSave += [
                    'app_name' => $request->app_name,
                    'allow_custom_order_values' => $request->allow_custom_order_values ?? '',
                    'sidebar_default_type' => $request->sidebar_default_type ?? '',
                    'language' => $request->language ?? 'en',
                    'language_rtl' => $request->language_rtl ?? false,
                    'timezone' => $request->timezone ?? 'UTC',
                ];
            }else{
                $input = $request->except(['_token', '_method']);

                $settingsToSave = array_map(function ($value) {
                    return is_null($value) ? false : $value;
                }, $input);

                if ($request->hasFile('background_image')) {
                    try {
                        $uuid = Str::uuid();
                        $image = $request->file('background_image');

                        $upload = Upload::create([
                            'uuid' => $uuid,
                        ]);
                        $upload->addMedia($image)->toMediaCollection('default');
                        $settingsToSave['background_image'] = $uuid;
                    } catch (\Exception $e) {
                        report($e);
                        Flash::error(__('Error occurred while uploading background image'));
                        return redirect()->back();
                    }
                }

                if(array_key_Exists('main_color', $settingsToSave) && $settingsToSave['main_color'] == false){
                    $settingsToSave['main_color'] = '';
                }
                if(array_key_Exists('secondary_color', $settingsToSave) && $settingsToSave['secondary_color'] == false){
                    $settingsToSave['secondary_color'] = '';
                }
                if(array_key_Exists('highlight_color', $settingsToSave) && $settingsToSave['highlight_color'] == false){
                    $settingsToSave['highlight_color'] = '';
                }
                if(array_key_Exists('background_color', $settingsToSave) && $settingsToSave['background_color'] == false){
                    $settingsToSave['background_color'] = '';
                }
                if(array_key_Exists('main_color_dark_theme', $settingsToSave) && $settingsToSave['main_color_dark_theme'] == false){
                    $settingsToSave['main_color_dark_theme'] = '';
                }
                if(array_key_Exists('secondary_color_dark_theme', $settingsToSave) && $settingsToSave['secondary_color_dark_theme'] == false){
                    $settingsToSave['secondary_color_dark_theme'] = '';
                }
                if(array_key_Exists('highlight_color_dark_theme', $settingsToSave) && $settingsToSave['highlight_color_dark_theme'] == false){
                    $settingsToSave['highlight_color_dark_theme'] = '';
                }
                if(array_key_Exists('background_color_dark_theme', $settingsToSave) && $settingsToSave['background_color_dark_theme'] == false){
                    $settingsToSave['background_color_dark_theme'] = '';
                }
            }

            setting($settingsToSave)->save();

            Flash::success(__('Settings updated successfully'));
        } else {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
        }
        return redirect()->back();

    }

}

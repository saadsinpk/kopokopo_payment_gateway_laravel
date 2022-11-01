<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\Upload;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Spatie\Async\Pool;

class AdminSettingController extends AppBaseController
{

    /*
     * Show general settings form page
     * @return Response
     */
    public function general(){
        return view('admin.settings.general');
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
    public function mail(){
        return view('admin.settings.mail');
    }

    /*
     * Show legal settings form page
     * @return Response
     */
    public function legal(){
        return view('admin.settings.legal');
    }

    /*
     * Clear cache of application
     * @return Redirect
     */
    public function clearCache(Request $request)
    {
        Artisan::call('optimize:clear');
        Flash::success(__('Cache successfully cleared!'));
        return redirect()->back();
    }


    /*
     * Store/Update general settings
     * @param Request $request
     * @return Redirect
     */
    public function storeSettings(Request $request){

        $previousUrl = url()->previous();
        $settingsToSave = array();
        if(Str::endsWith($previousUrl,'admin.settings/general')) {
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
                        'app_name' => $request->app_name,
                        'app_logo' => $uuid
                    ];
                } catch (\Exception $e) {
                    report($e);
                    Flash::error(__('Error occurred while uploading logo'));
                    return redirect()->back();
                }
            }

            $settingsToSave += [
                'theme_color' => $request->theme_color,
                'nav_color' => $request->nav_color,
                'logo_bg_color' => $request->logo_bg_color ?? '',
            ];

        }elseif(Str::endsWith($previousUrl,'admin.settings/payments_api')) {
            $input = $request->only(['enable_paddle', 'enable_stripe']);
            $arrayEnv = array();
            if($request->enable_stripe){
                $arrayEnv['STRIPE_KEY'] = $request->stripe_key;
                $arrayEnv['STRIPE_SECRET'] = $request->stripe_secret;
            }
            if($request->enable_paddle){
                $arrayEnv['PADDLE_VENDOR_ID'] = $request->paddle_vendor_id;
                $arrayEnv['PADDLE_VENDOR_AUTH_CODE'] = $request->paddle_vendor_auth_code;
                $arrayEnv['PADDLE_PUBLIC_KEY'] = $request->paddle_public_key;
                $arrayEnv['PADDLE_SANDBOX'] = $request->paddle_sandbox??0;
            }

            if(count($arrayEnv) > 0){
                setEnvironmentValues($arrayEnv);
            }


            Artisan::call('optimize:clear');

            $settingsToSave = array_map(function ($value) {
                return is_null($value) ? false : $value;
            }, $input);
        }else{
            $input = $request->except(['_token', '_method']);

            $settingsToSave = array_map(function ($value) {
                return is_null($value) ? false : $value;
            }, $input);
        }

        setting($settingsToSave)->save();

        Flash::success(__('Settings updated successfully'));

        return redirect()->back();




    }

}

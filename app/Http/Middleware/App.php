<?php

namespace App\Http\Middleware;

use App\Repositories\UploadRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class App
{

    protected $uploadRepository;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if( isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe' ) {
                die("Please, access the live preview outside of an iframe: <a href='".env('APP_URL')."' target='_blank'>".env("APP_URL")."</a>");
            }
            app()->setLocale(setting('language', app()->getLocale()));
            Carbon::setLocale(app()->getLocale());

            $this->uploadRepository = new UploadRepository(app());
            $upload = $this->uploadRepository->getByUuid( setting('app_logo', ''));
            $appLogo = asset('img/logo_default.png');
            if ($upload && $upload->hasMedia('default')) {
                $appLogo = $upload->getFirstMediaUrl('default');
            }
            view()->share('app_logo', $appLogo);

        } catch (\Exception $exception) {
            \Log::error($exception);
        }
        view()->share('icons', true);
        return $next($request);
    }
}

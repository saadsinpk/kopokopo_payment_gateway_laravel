<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Permissions
{

    private $exceptNames = [
        'LaravelInstaller*',
        'LaravelUpdater*',
        'debugbar*'
    ];

    private $exceptControllers = [
        'LoginController',
        'ForgotPasswordController',
        'ResetPasswordController',
        'RegisterController',
    ];


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $permission = $request->route()->getName();
        if($request->route()->getPrefix() == '/admin') {
            if (!is_null($permission) && !is_null(auth()->user()) && $this->match($request->route()) && auth()->user()->canNot($permission)) {
                if ($permission == 'admin.dashboard') {
                    return redirect(route('admin.users.edit', auth()->user()->id));
                }
                throw new UnauthorizedException(403, __('You have no permission to do this action') . $permission);
            }
        }
        return $next($request);
    }

    private function match(\Illuminate\Routing\Route $route)
    {

        if ($route->getName() === null) {
            return false;
        } else {
            try{
                $controller = $route->getController();
            }catch (\Throwable $e){
                return false;
            }
            if (in_array(class_basename($controller), $this->exceptControllers)) {
                return false;
            }

            foreach ($this->exceptNames as $except) {
                if (Str::is($except, $route->getName())) {
                    return false;
                }
            }
        }
        return true;
    }
}

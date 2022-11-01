<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
class RefreshPermissionsSeeder extends Seeder
{
    //$ php artisan db:seed --class=RefreshPermissionsSeeder
    private $exceptNames = [
        'LaravelInstaller*',
        'LaravelUpdater*',
        'debugbar*',
        'cashier*',
        'ignition.*',
        'api*',
        'password.*'
    ];

    private $exceptControllers = [
        'LoginController',
        'ForgotPasswordController',
        'ResetPasswordController',
        'RegisterController',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routeCollection = Route::getRoutes();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        foreach ($routeCollection->getRoutes() as $route) {
            if ($this->match($route)) {
                try{
                    Permission::findOrCreate($route->getName(),'web');
                }catch (Exception $e){

                }
            }
        }
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
            if (preg_match('/API/', class_basename($controller))) {
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

<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private $uploadRepository;
    private $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UploadRepository $uploadRepository, RoleRepository $roleRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
    }



    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($service, Request $request)
    {
        if ($request->query('mobile') == "true") {
            Session::flush();
            session([
                'mobile' => true,
                'role' => $request->query('role'),
                'deviceToken' => $request->query('deviceToken'),
                'security_token' => $request->query('security_token')
            ]);
        }

        return Socialite::driver($service)->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($service, Request $request)
    {
        $userSocial = Socialite::driver($service)->user();
        $user = User::where('email', $userSocial->email)->first();
        if (!$user) {
            $user = new User;
            $user->name = $userSocial->name;
            $user->email = $userSocial->email;
            $user->password = bcrypt(Str::random());
            $user->save();
            if (null !== session('role', null)) {
                $defaultRoles = $this->roleRepository->findByField('name', session('role'));
                if (!isset($defaultRoles)) {
                    $defaultRoles = $this->roleRepository->findByField('default', '1');
                }
                if(session('role') == 'driver'){
                    Courier::create(['user_id' => $user->id, 'active' => false, 'using_app_pricing' => true]);
                }
                $defaultRoles = $defaultRoles->pluck('name')->toArray();
            } else {
                $defaultRoles = $this->roleRepository->findByField('default', '1');
                $defaultRoles = $defaultRoles->pluck('name')->toArray();
            }
            $user->assignRole($defaultRoles);

            try {
                $upload = $this->uploadRepository->create(['uuid' => $userSocial->token]);
                $upload->addMediaFromUrl($userSocial->avatar_original)
                    ->withCustomProperties(['uuid' => $userSocial->token])
                    ->toMediaCollection('avatar');

                $cacheUpload = $this->uploadRepository->getByUuid($userSocial->token);
                if (isset($cacheUpload)) {
                    $mediaItem = $cacheUpload->getMedia('avatar')->first();
                    $mediaItem->copy($user, 'avatar');
                }
            } catch (\Exception $e) {
            }
        }
        auth()->login($user, true);
        if (session('mobile', false)) {
            $user->security_token = session('security_token', '');
            $user->save();
            Session::flush();
            auth()->logout();
            return "<h1 style='text-align:center;font-family:sans-serif'>Você está logado, volte para o app</h1>";
        } else {
            if (auth()->user()->hasRole('admin')) {
                return redirect(route('admin.dashboard'));
            } else {
                return redirect(route('home'));
            }
        }
    }
}

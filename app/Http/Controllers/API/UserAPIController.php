<?php

namespace App\Http\Controllers\API;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourierAPIRequest;
use App\Http\Requests\UpdateUserAPIRequest;
use App\Models\Courier;
use App\Models\User;
use App\Repositories\RoleRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserAPIController extends Controller
{
    private $userRepository;
    private $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo)
    {
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
    }


    /*
     * Register a new user with default role
     */
    public function register(CreateCourierAPIRequest $request)
    {
        try {

            DB::beginTransaction();
            $default = $this->roleRepository->findByField('default', true)->pluck('name')->toArray();
            if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                $user = Auth::user()->makeVisible('api_token');
                if (!Auth::user()->hasRole($default)) {
                    $user->assignRole($default);
                }
                $user->api_token = Str::random(60);
                $user->makeVisible('api_token');
                $user->save();
                DB::commit();
                return $this->sendResponse($user, 'Account already created');
            }
            $user = User::create($request->all() + ['api_token' => Str::random(60)]);
            if ($request->has('photo_url')) {
                try {
                    $customUuid = Str::random();
                    $upload = $this->uploadRepository->create(['uuid' => $customUuid]);
                    $upload->addMediaFromUrl($request->photo_url)
                        ->withCustomProperties(['uuid' => $customUuid])
                        ->toMediaCollection('avatar');
                    $cacheUpload = $this->uploadRepository->getByUuid($customUuid);
                    if (isset($cacheUpload)) {
                        $mediaItem = $cacheUpload->getMedia('avatar')->first();
                        $mediaItem->copy($user, 'avatar');
                    }
                } catch (\Exception $e) {
                    //
                }
            }
            $user->makeVisible('api_token');
            $user->assignRole($default);
            DB::commit();
            return $this->sendResponse($user, 'Account created successfully');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /*
     * Login as user
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        try {

            if (!Auth::attempt($credentials, $request->get('remember_me', false))) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $user = Auth::user()->makeVisible('api_token');
            auth()->user()->api_token = Str::random(60);
            auth()->user()->save();
            return $this->sendResponse($user, 'Login successfull');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
    /*
     * Check api token
     */
    public function verifyLogin()
    {
        try {
            $user = Auth::user()->makeVisible('api_token');

            return $this->sendResponse($user, 'Login verified');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
    /*
     * Forgot my password for user
     */
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return $this->sendResponse(['status' => __($status)], 'Link sended');
            } else {
                return $this->sendError(['email' => __($status)]);
            }
        } catch (\Exception $e) {
            report($e);
            return $this->sendError($e->getMessage());
        }
    }

    /*
     * Update user profile picture though API
     */
    public function updateProfilePicture(Request $request)
    {
        try {
            $input = $request->only('image');
            if (isset($input['image']) && $input['image']) {
                $user = auth()->user()->makeVisible('api_token');
                $user->addMediaFromBase64($input['image'])
                    ->toMediaCollection('default');
                Media::where('id', '!=', $user->media->last()->id)
                    ->where('model_id', $user->id)
                    ->where('model_type', 'App\Models\User')
                    ->delete();
                $user = User::find($user->id)->makeVisible('api_token');
                Auth::setUser($user);
            }
            return $this->sendResponse($user, 'Image updated successfully');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /*
     * Check if user login with social network was sucessfull
     */
    function loginCheck(Request $request)
    {
        $validate = Carbon::now()->subMinute(15);
        if (empty($request->security_token) || strlen($request->security_token) < 30) {
            return json_encode(['success' => 0]);
        }
        $user = User::where('security_token', $request->security_token)->where('updated_at', '>=', $validate->format('Y-m-d H:i:s'))->first();
        if ($user) {
            $user->api_token = Str::random(60);
            $user->makeVisible('api_token');
            $user->save();
            return $this->sendResponse($user, 'User retrieved successfully');
        } else {
            return json_encode(['success' => 0]);
        }
    }


    /*
     * Update user profile data though API
     */
    public function updateProfile(UpdateUserAPIRequest $request)
    {
        $user = Auth::user()->makeVisible('api_token');
        $data = $request->only('name', 'email', 'phone');
        try {
            if(isset($request->password)){
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);
            return $this->sendResponse($user, 'Profile updated');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /*
     * Update user fcm token
     */
    public function updateToken(Request $request)
    {
        $request->validate([
            'firebase_token' => 'required|string',
        ]);
        try {
            $user = auth()->user();
            $user->firebase_token = $request->get('firebase_token');
            $user->save();
            return $this->sendResponse(['success' => 1,'token' => $user->firebase_token], 'Notification token saved successfully');
        } catch (Exception $e) {
            report($e);
            return $this->sendError('Error at update');
        }
    }
    
    /*
     * Delete private data from user
     */
    function deleteAccount()
    {
        try {
            $user = Auth::user();
            $user->media->each->delete();
            $user->name = 'userdeleted';
            $user->phone = null;
            $user->email = 'userdeleted ' . Str::random(30);
            $user->password = 'userdeleted ' . Str::random(30);
            $user->api_token = null;
            $user->firebase_token = null;
            $user->save();

            if($user->courier()->exists()){
                $user->courier()->update(['active' => false, 'last_location_at' => null, 'lat' => null, 'lng' => null]);
            }
            
            return $this->sendResponse(true, 'Account deleted successfully');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
}

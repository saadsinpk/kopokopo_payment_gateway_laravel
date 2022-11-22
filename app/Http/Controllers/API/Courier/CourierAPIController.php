<?php

namespace App\Http\Controllers\API\Courier;

use App\Repositories\CourierRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourierAPIRequest;
use App\Models\Courier;
use App\Models\User;
use App\Repositories\RoleRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CourierAPIController extends Controller
{
    private $roleRepository;
    private $courierRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RoleRepository $roleRepository,CourierRepository $courierRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->courierRepository = $courierRepository;
    }


    /*
     * Register a new user as courier
     */
    public function register(CreateCourierAPIRequest $request)
    {

        try {

            DB::beginTransaction();
            if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                $user = Auth::user()->makeVisible('api_token');
                if (!Auth::user()->hasRole('driver')) {
                    Courier::updateOrCreate(['user_id' => $user->id],['active' => false, 'using_app_pricing' => true]);
                    Auth::user()->assignRole('driver');
                }
                $user->api_token = Str::random(60);
                $user->makeVisible('api_token');
                $user->save();
                DB::commit();
                return $this->sendResponse($user, 'Account already created');
            }
            $request->validate([
                'email' => 'unique:users,email',
            ]);

            $user = User::create($request->all() + ['api_token' => Str::random(60), 'device_token' => $request->input('device_token', '')]);
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
            Courier::create(['user_id' => $user->id, 'active' => false, 'using_app_pricing' => true]);
            $user->makeVisible('api_token');

            $driver = $this->roleRepository->findByField('name', 'driver')->pluck('name')->toArray();
            $user->assignRole($driver);
            $user->assignRole('driver');
            DB::commit();
            return $this->sendResponse($user, 'Account created successfully');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return $this->sendError(trans('error.error'));
        }
    }

    /*
     * Login as courier
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        try {

            if (!Auth::attempt($credentials) || !Auth::user()->hasRole('driver')) {
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
     * Check api token of courier
     */
    public function verifyLogin()
    {
        try {
            $user = Auth::user()->makeVisible('api_token');

            if (!Auth::user()->hasRole('driver')) {
                return $this->sendResponse(null, 'Status not regular', 401);
            }
            return $this->sendResponse($user, 'Login verified');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->validate([
            'using_app_pricing' => 'boolean',
            'base_distance' => 'numeric',
            'additional_distance_pricing' => 'numeric',
            'return_distance_pricing' => 'numeric',
            'base_price' => 'numeric',
            'additional_stop_tax' => 'numeric',
        ]);

        if (!setting('allow_custom_order_values', true)) {
            return $this->sendError(__('Custom order values not allowed'));
        }

        try {
            $user = User::find(Auth::user()->id);
            $user->courier->update($settings);
            return $this->sendResponse($user, 'Settings updated successfully');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }


    public function getDeliveryActive()
    {
        try {
            $active = Auth::user()->courier->active;

            return $this->sendResponse(['active' => __($active)], 'Delivery status retrieved');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    public function updateDeliveryActive(Request $request)
    {
        $data = $request->validate([
            'active' => 'required|boolean'
        ]);

        try {
            $user = Auth::user();

            $user->courier()->update(['active' => $data['active']]);
            $user->save();

            $active = Auth::user()->courier->active;

            return $this->sendResponse(['active' => __($active)], 'Delivery status updated');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    public function updateLocation(Request $request)
    {
        $location = $request->validate([
            'lat' => 'required|between:-90,90',
            'lng' => 'required|between:-180,180'
        ]);
        try {
            $user = Auth::user();
            $user->courier()->update(array_merge([
                'last_location_at' => Carbon::now()
            ], $location));
            return $this->sendResponse(null, 'Location updated successfully');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /*
     * Find the couriers near of pickup location
     */
    public function findNearBy(Request $request){
        try {
            $pickupPlace = json_decode($request->input('collect_address_data', '{}'), true);
            if(isset($pickupPlace['geometry']['location']['lat'])){
                $lat = (float)$pickupPlace['geometry']['location']['lat'];
                $lng = (float)$pickupPlace['geometry']['location']['lng'];

                //search the most near couriers
                $couriers = $this->courierRepository->getCouriersNearOf($lat,$lng);

                $couriersFound = array();
                foreach ($couriers as $courier) {
                    $couriersFound[$courier->id] = [
                        'id' => $courier->id,
                        'slug' => $courier->slug,
                        'name' => $courier->user->name,
                        'distance' => number_format($courier->distance, 1).' '.setting('distance_unit', 'mi'),
                        'avatar' => $courier->user->media->first()->thumb??'',
                        'orders_count' => getTextForOrderCount($courier->orders_count),
                    ];
                }
            }else{
                $couriersFound = array();
            }

            return $this->sendResponse(array_values($couriersFound), 'Couriers founded');

        }catch(\Exception $e){
            report($e);
            return $this->sendError(__('An error has occurred'),500);
        }
    }
}

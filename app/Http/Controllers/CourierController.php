<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Order;
use App\Repositories\CourierRepository;
use App\Repositories\OfflinePaymentMethodRepository;
use App\Repositories\UploadRepository;

class CourierController extends AppBaseController
{

    private $courierRepository;
    private $offlinePaymentMethodRepository;
    public function __construct(CourierRepository $courierRepository,OfflinePaymentMethodRepository $offlinePaymentMethodRepository)
    {
        $this->courierRepository = $courierRepository;
        $this->offlinePaymentMethodRepository = $offlinePaymentMethodRepository;
    }

    /*
     * Courier screen to order directly to him
     * @parameter string $slug
     */
    public function index($slug){
        try {
            $courier = $this->courierRepository->with('user')->findByField('slug', $slug)->firstOrFail();
            $user = auth()->user();
            $lastCollectAddressData = '';
            $lastCollectAddress = '';
            if($user){
                $lastOrder = Order::select(['save_pickup_location_for_next_order','pickup_location_data','pickup_location'])->where('user_id',$user->id)->orderBy('id', 'desc')->first();
                if($lastOrder && $lastOrder->save_pickup_location_for_next_order){
                    $lastCollectAddressData = $lastOrder->pickup_location_data;
                    $lastCollectAddress = $lastOrder->pickup_location;
                }
            }

            $offlinePaymentMethods = $this->offlinePaymentMethodRepository->all();
            $background_image = (new UploadRepository(app()))->getByUuid( setting('background_image', ''));
            if ($background_image && $background_image->hasMedia('default')) {
                $background_image = $background_image->getFirstMediaUrl('default');
            }

            return view('couriers.index',compact('background_image','courier','lastCollectAddressData','lastCollectAddress','offlinePaymentMethods'));
        }catch (\Exception $e) {
            report($e);
            return redirect()->route('home');
        }
    }

}

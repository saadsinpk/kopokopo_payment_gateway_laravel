<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Category;
use App\Models\Order;
use App\Repositories\OfflinePaymentMethodRepository;
use App\Repositories\UploadRepository;

class HomeController extends AppBaseController
{

    private $offlinePaymentMethodRepository;

    public function __construct(OfflinePaymentMethodRepository $offlinePaymentMethodRepository)
    {
        $this->offlinePaymentMethodRepository = $offlinePaymentMethodRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        $categories = Category::all();
        return view('home',compact('background_image','lastCollectAddressData','lastCollectAddress','offlinePaymentMethods','categories'));
    }
}

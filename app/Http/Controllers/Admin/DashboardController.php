<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends AppBaseController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $statuses = [
            'waiting' => [
                'name' => trans('general.order_status_list.waiting'),
                'color' => '#dddddd',
                'only_recent' => true,
            ],
            'pending' => [
                'name' => trans('general.order_status_list.pending'),
                'color' => '#F7CB73',
                'only_recent' => false,
            ],
            'accepted' => [
                'name' => trans('general.order_status_list.accepted'),
                'color' => '#F29339',
                'only_recent' => false,
            ],
            'rejected' => [
                'name' => trans('general.order_status_list.rejected'),
                'color' => '#D9512C',
                'only_recent' => true,
            ],
            'collected' => [
                'name' => trans('general.order_status_list.collected'),
                'color' => '#1974D3',
                'only_recent' => false,
            ],
            'delivered' => [
                'name' => trans('general.order_status_list.delivered'),
                'color' => '#394F6B',
                'only_recent' => false,
            ],
            'completed' => [
                'name' => trans('general.order_status_list.completed'),
                'color' => '#077E8C',
                'only_recent' => true,
            ],
            'cancelled' => [
                'name' => trans('general.order_status_list.cancelled'),
                'color' => '#D9512C',
                'only_recent' => true,
            ],
        ];

        //numbers in panels
        $ordersInProgressCount = Order::whereIn('order_status',['pending','accepted','collected','delivered'])->count();
        $activeCouriersCount = Courier::where('active',1)->count();
        $customersCount = User::count();

        //last 6 months revenue chart
        $chart = [];
        for($i=5;$i>=0;$i--){
            $chart[date('m/y',strtotime('-'.$i.' months'))] = [
                'revenue' => Order::where('created_at','>=',date('Y-m-01',strtotime('-'.$i.' months')))->where('created_at','<',date('Y-m-01',strtotime('-'.($i-1).' months')))->whereIn('order_status',['completed','delivered'])->sum('total_value'),
                'count' => Order::where('created_at','>=',date('Y-m-01',strtotime('-'.$i.' months')))->where('created_at','<',date('Y-m-01',strtotime('-'.($i-1).' months')))->whereIn('order_status',['completed','delivered'])->count(),
            ];
        }



        return view('admin.dashboard',compact('statuses','ordersInProgressCount','activeCouriersCount','customersCount','chart'));
    }

    /*
     * Return the order list for the dashboard kanban list
     */
    function ajaxGetOrders(Request $request){
        $status = $request->get('status');

        $onlyRecent = $request->get('only_recent',false);
        $orders = Order::with('user','courier','courier.user')->where('order_status',$status);
        if($onlyRecent){
            //last 24h hours orders only
            $orders = $orders->where('created_at','>=',date('Y-m-d H:i:s',strtotime('-24 hours')));
        }
        $orders = $orders->orderBy('id','desc')->get();

        $toReturn = [];
        foreach($orders as $order){
            $deliveryLocation = '';
            foreach(json_decode($order->delivery_locations_data) as $k => $location){
                $deliveryLocation .= $location->formatted_address.' - '.$location->number.' | ';
            }
            if(strlen($deliveryLocation) > 0){
                $deliveryLocation = substr($deliveryLocation,0,-3);
            }
            $toReturn[] = [
                'id' => $order->id,
                'courier_name' => $order->courier->user->name,
                'customer_name' => $order->user->name,
                'pickup_location' => $order->pickup_location,
                'delivery_location' => $deliveryLocation,
                'return_location' => $order->need_return_to_pickup_location,
                'distance' => $order->distance,
                'courier_value' => getPrice($order->courier_value),
                'app_value' => getPrice($order->app_value),
                'total' => getPrice($order->total_value),
                'link' => route('admin.orders.show',$order->id),
                'created_at' => getDateHumanFormat($order->created_at,false),
            ];
        }

        return $toReturn;

    }



}

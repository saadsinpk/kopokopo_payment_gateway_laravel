<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class ReportController extends AppBaseController
{

    /*
     * Orders By Date Report
     */
    public function ordersByDate(Request $request)
    {
        if($request->isMethod('post')){
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if(empty($start_date) || empty($end_date)){
                Flash::error(__('Please select start and end date'));
                return redirect()->back();
            }
            $orders = \App\Models\Order::with(['user','courier','courier.user'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->orderBy('created_at','asc');
            if($request->get('only_completed')){
                $orders = $orders->where('order_status','completed');
            }
            $orders = $orders->get();

            return view('admin.reports.orders_by_date', compact('orders'));
        }
        return view('admin.reports.orders_by_date');
    }

    /*
     * Orders By Driver Report
     */
    public function ordersByDriver(Request $request)
    {
        $couriers = \App\Models\Courier::join('users','users.id','=','couriers.user_id')->orderBy('users.name','asc')->pluck('users.name','couriers.id');
        if($request->isMethod('post')){
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if(empty($start_date) || empty($end_date)){
                Flash::error(__('Please select start and end date'));
                return redirect()->back();
            }
            if(empty($request->get('courier_id'))){
                Flash::error(__('Please select the driver'));
                return redirect()->back();
            }
            $orders = \App\Models\Order::with(['user','courier','courier.user'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('courier_id',$request->get('courier_id'))
                ->orderBy('created_at','asc');
            if($request->get('only_completed')){
                $orders = $orders->where('order_status','completed');
            }
            $orders = $orders->get();

            return view('admin.reports.orders_by_driver', compact('orders','couriers'));
        }

        return view('admin.reports.orders_by_driver')->with('couriers',$couriers);
    }


    /*
     * Orders By Customer Report
     */
    public function ordersByCustomer(Request $request)
    {
        $customers = \App\Models\User::orderBy('name','asc')->pluck('name','id');
        if($request->isMethod('post')){
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if(empty($start_date) || empty($end_date)){
                Flash::error(__('Please select start and end date'));
                return redirect()->back();
            }
            if(empty($request->get('customer_id'))){
                Flash::error(__('Please select the customer'));
                return redirect()->back();
            }
            $orders = \App\Models\Order::with(['user','courier','courier.user'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('user_id',$request->get('customer_id'))
                ->orderBy('created_at','asc');
            if($request->get('only_completed')){
                $orders = $orders->where('order_status','completed');
            }
            $orders = $orders->get();

            return view('admin.reports.orders_by_customer', compact('orders','customers'));
        }

        return view('admin.reports.orders_by_customer')->with('customers',$customers);
    }

}

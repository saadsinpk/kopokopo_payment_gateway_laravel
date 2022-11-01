@extends('layouts.app')
@section('title')
    {{__('Courier Payouts')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('Courier Payouts')}}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('admin.courierPayouts.create')}}" class="btn btn-primary form-btn">{{__('Courier Payout')}} <i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>{{__('Couriers Payout List')}}</h4>
            </div>

            <div class="card-body">
                @include('flash::message')
                @include('stisla-templates::common.errors')
                @include('admin.courier_payouts.table')
            </div>
        </div>
       <div class="card">
           <div class="card-header">
               <h4>{{__('Couriers Payout Summary')}}</h4>
           </div>

            <div class="card-body">
                @include('admin.courier_payouts.summary')
            </div>
       </div>
   </div>

    </section>
@endsection


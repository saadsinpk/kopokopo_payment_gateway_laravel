@extends('layouts.app')
@section('title')
    {{__('Orders')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('general.order_plural')}}</h1>

        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('flash::message')
                @include('stisla-templates::common.errors')
                @include('admin.orders.table')
            </div>
       </div>
   </div>

    </section>
@endsection


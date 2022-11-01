@extends('layouts.app')
@section('title')
    {{__('Couriers')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <div class="col-6 text-left">
            <h1>{{__('Couriers')}}</h1>
            </div>
            @can('admin.users.create')
                <div class="col-6 text-right">
                    <a class="btn btn-primary form-btn" href="{{route('admin.users.create')}}?role=driver">{{__('crud.add_new')}} <i class="fas fa-plus"></i></a>
                </div>
            @endcan
        </div>

    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('flash::message')
                @include('stisla-templates::common.errors')
                @include('admin.couriers.table')
            </div>
       </div>
   </div>

    </section>
@endsection


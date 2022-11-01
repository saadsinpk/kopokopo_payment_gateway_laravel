@extends('layouts.app')
@section('title')
    {{__('Create Offline Payment Method')}}
@endsection
@section('content')
    <section class="section">


            <div class="section-body">
                <div class="row">
                    <div class="col-md-4">
                        @include('layouts.admin.settings.sidebar')
                    </div>
                    <div class="col-md-8">
                       <div class="card">
                           <div class="card-header">
                               <div class="col-6">
                                   <h4>{{ __('Create Offline Payment Method') }}</h4>
                               </div>
                               <div class="col-6 text-right">
                                   <a class="btn btn-primary form-btn" href="{{route('admin.offlinePaymentMethods.index')}}">{{__('crud.back')}}</a>
                               </div>
                           </div>
                           <div class="card-body ">
                               @include('flash::message')
                               @include('stisla-templates::common.errors')
                                {!! Form::open(['route' => 'admin.offlinePaymentMethods.store','files' => true]) !!}
                                    <div class="row">
                                        @include('admin.offline_payment_methods.fields')
                                    </div>
                                {!! Form::close() !!}
                           </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection

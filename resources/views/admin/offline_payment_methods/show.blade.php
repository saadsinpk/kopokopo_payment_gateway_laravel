@extends('layouts.app')
@section('title')
    {{__('Offline Payment Method Details')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
        <h1>{{__('Offline Payment Method Details')}}</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('admin.offlinePaymentMethods.index') }}"
                 class="btn btn-primary form-btn float-right">{{__('Back')}}</a>
        </div>
      </div>
        @include('flash::message')
   @include('stisla-templates::common.errors')
    <div class="section-body">
           <div class="card">
            <div class="card-body">
                    @include('admin.offline_payment_methods.show_fields')
            </div>
            </div>
    </div>
    </section>
@endsection

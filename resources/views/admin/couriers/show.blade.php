@extends('layouts.app')
@section('title')
    {{__('Courier Details')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
        <h1>{{__('Courier Details')}}</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('admin.couriers.index') }}"
                 class="btn btn-primary form-btn float-right">{{__('Back')}}</a>
        </div>
      </div>
        @include('flash::message')
   @include('stisla-templates::common.errors')
    <div class="section-body">
           <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('admin.couriers.show_fields')
                </div>
            </div>
            </div>
    </div>
    </section>
@endsection

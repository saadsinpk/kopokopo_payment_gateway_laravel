@extends('layouts.app')
@section('title')
    {{__('Roles')}}
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
                               <h4>{{ __('Roles') }}</h4>
                           </div>
                           <div class="col-6 text-right">
                               <a class="btn btn-primary form-btn" href="{{route('admin.roles.create')}}">{{__('crud.add_new')}} <i class="fas fa-plus"></i></a>
                           </div>
                       </div>
                        <div class="card-body">
                            @include('flash::message')
                            @include('stisla-templates::common.errors')
                            @include('admin.roles.table')
                        </div>
                   </div>
                </div>
            </div>
       </div>

    </section>
@endsection


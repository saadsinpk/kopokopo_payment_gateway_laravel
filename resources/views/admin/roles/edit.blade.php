@extends('layouts.app')
@section('title')
    {{__('Edit Role')}}
@endsection
@section('content')
    <section class="section">
        <div class="content">

          <div class="section-body">
             <div class="row">
                 <div class="col-md-4">
                     @include('layouts.admin.settings.sidebar')
                 </div>
                 <div class="col-md-8">
                     <div class="card">
                         <div class="card-header">
                             <div class="col-6">
                                 <h4>{{ __('Edit Role') }}</h4>
                             </div>
                             <div class="col-6 text-right">
                                 <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">{{__('crud.back')}}</a>
                             </div>
                         </div>
                         <div class="card-body ">
                             @include('flash::message')
                             @include('stisla-templates::common.errors')
                            {!! Form::model($role, ['route' => ['admin.roles.update', $role->id], 'method' => 'patch']) !!}
                            <div class="row">
                                @include('admin.roles.fields')
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

@extends('layouts.app')
@section('title')
    {{__('Categories')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('Categories')}}</h1>

            <div class="section-header-breadcrumb">
                <a href="{{ route('admin.categories.create')}}" class="btn btn-primary form-btn">{{__('Category')}} <i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('flash::message')
                @include('stisla-templates::common.errors')
                @include('admin.categories.table')
            </div>
       </div>
   </div>

    </section>
@endsection


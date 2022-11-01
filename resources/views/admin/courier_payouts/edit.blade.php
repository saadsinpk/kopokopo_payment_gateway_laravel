@extends('layouts.app')
@section('title')
    {{__('Edit Courier Payout')}}
@endsection
@section('content')
    <section class="section">
            <div class="section-header">
                <h3 class="page__heading m-0">{{__('Edit Courier Payout')}}</h3>
                <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                    <a href="{{ route('admin.courierPayouts.index') }}"  class="btn btn-primary">{{__('Back')}}</a>
                </div>
            </div>
  <div class="content">
              @include('stisla-templates::common.errors')
              <div class="section-body">
                 <div class="row">
                     <div class="col-lg-12">
                         <div class="card">
                             <div class="card-body ">
                                    {!! Form::model($courierPayout, ['route' => ['admin.courierPayouts.update', $courierPayout->id], 'method' => 'patch']) !!}
                                        <div class="row">
                                            @include('admin.courier_payouts.fields')
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

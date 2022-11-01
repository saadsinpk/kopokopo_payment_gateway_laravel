@extends('layouts.app')
@section('title')
    {{__('Permissions')}}
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
                                <h4>{{ __('Permissions') }}</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a class="nav-link pt-1 float-right" href="{{route('admin.settings.clear_cache')}}"><i class="fas fa-trash"></i> {{trans('Clear cache')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('flash::message')
                            @include('stisla-templates::common.errors')
                            @include('admin.permissions.table')
                        </div>
                    </div>
                </div>
            </div>
       </div>

    </section>
@endsection
@push('scripts')
    <!-- iCheck -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('change','.permission',function(){
               let $this = $(this);
               let checked = $this.is(':checked');
               let data_role = $this.attr('data-role-id');
               let data_permission_json_string = $this.attr('data-permission');
               $.ajax({
                   url: "{{route('admin.permissions.update')}}",
                   type: "POST",
                   data: {
                       _token: "{{csrf_token()}}",
                       allow:(checked?1:0),
                       roleId: data_role,
                       permission_json_string: data_permission_json_string
                   },
                   success: function(data){
                       console.log(data);
                   }
               });
            });
        })
    </script>
@endpush


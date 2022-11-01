@section('title', __('Terms of Service'))
@extends('layouts.auth.auth')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        {!! setting('terms_of_service') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@extends('layouts.app')
@section('title')
    {{ __('Orders By Driver') }}
@endsection
@push('page_css')
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
@endpush
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Orders By Driver') }}</h1>
            <div class="section-header-breadcrumb">
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Filter') }}</h4>
                </div>

                <div class="card-body">
                    @include('flash::message')
                    @include('stisla-templates::common.errors')
                    {{ Form::open(['method' => 'POST']) }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">{{ __('Orders From') }}</label>
                                {{ Form::date('start_date', request()->get('start_date', null), ['class' => 'form-control', 'id' => 'start_date']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">{{ __('To') }}</label>
                                {{ Form::date('end_date', request()->get('end_date', null), ['class' => 'form-control', 'id' => 'end_date']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="courierId">{{ __('Driver') }}</label>
                                {{ Form::select('courier_id', $couriers, request()->get('courier_id'), ['class' => 'form-control select2', 'id' => 'courierId']) }}
                            </div>
                        </div>
                        <div class="col-md-6 mt-md-5">
                            <div class="form-group checkbox icheck">
                                <input type="hidden" name="only_completed" value="0">
                                {{ Form::checkbox('only_completed', request()->get('only_completed', 1), ['id' => 'onlyCompleted']) }}
                                <label for="onlyCompleted">{{ __('Only Completed Orders') }}</label>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            {{ Form::submit(__('Filter'), ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Report') }}</h4>
                    @if (isset($orders))
                        <div class="card-header-action">
                            <button id="btnPrint" class="btn active"><i class="fas fa-print"></i> Print</button>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    @if (!isset($orders))
                        <div class="alert alert-info text-center">
                            {{ __('Filter the dates first to load the report') }}
                        </div>
                    @else
                        <div class="table-responsive" id="reportGenerated">
                            <table class="table table-bordered table-md">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Driver') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Distance') }}</th>
                                        <th>{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($total = 0)
                                    @foreach ($orders as $order)
                                        @php($total += $order->total_value)
                                        <tr>
                                            <td>{{ $order->created_at->translatedFormat('j F Y H:i:s') }}</td>
                                            <td>{{ $order->courier->user->name }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ trans('general.order_status_list.' . $order->order_status) }}</td>
                                            <td>{{ $order->distance . ' ' . setting('distance_unit', 'mi') }}</td>
                                            <td>{!! getPrice($order->total_value) !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="text-align: center">{!! __(':orders orders', ['orders' => count($orders)]) !!}</td>
                                        <td colspan="4" style="text-align: right">{{ __('Total') }}</td>
                                        <td>{!! getPrice($total) !!}</td>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </section>
@endsection
@push('scripts')
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if (isset($orders))
                $('#btnPrint').click(function() {
                    width = 210;
                    var printHtml = window.open('', 'PRINT', 'width=' + width + 'mm');


                    printHtml.document.write(document.getElementById("reportGenerated").innerHTML);

                    printHtml.document.close();
                    setTimeout(function() {
                        printHtml.focus();
                        printHtml.print();
                        printHtml.close();
                    }, 1500);

                    return true;
                });
            @endif
        });
    </script>
@endpush

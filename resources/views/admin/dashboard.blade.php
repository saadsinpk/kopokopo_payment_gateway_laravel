@extends('layouts.app')
@section('title')
    {{ __('Admin Dashboard') }}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/kanban.css')}}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">{{__('Dashboard')}}</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <h6>Orders Revenue Chart</h6>
                    <canvas id="myChart"></canvas>
                </div>
                <div class="col-md-4">
                    <div class="card card-info card-body">
                        <h3><i class="fa fa-clock"></i> {{$ordersInProgressCount}}</h3>
                        <p>{{__('Orders in Progress')}}</p>
                    </div>
                    <div class="card card-warning card-body">
                        <h3><i class="fa fa-motorcycle"></i> {{$activeCouriersCount}}</h3>
                        <p>{{__('Active Couriers')}}</p>
                    </div>
                    <div class="card card-success card-body">
                        <h3><i class="fa fa-users"></i> {{$customersCount}}</h3>
                        <p>{{__('Customers')}}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="boards overflow-auto p-0" id="boardsContainer">

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{asset('plugins/chart-js/dist/chart.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            const slider = document.querySelector('.boards');
            let mouseDown = false;
            let startX, scrollLeft;

            let startDragging = function (e) {
                mouseDown = true;
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            };
            let stopDragging = function (event) {
                mouseDown = false;
            };

            slider.addEventListener('mousemove', (e) => {
                e.preventDefault();
                if(!mouseDown) { return; }
                const x = e.pageX - slider.offsetLeft;
                const scroll = x - startX;
                slider.scrollLeft = scrollLeft - scroll;
            });
                slider.addEventListener('mousedown', startDragging, false);
                slider.addEventListener('mouseup', stopDragging, false);
                slider.addEventListener('mouseleave', stopDragging, false);
            let cardBeignDragged;
            let dropzones = document.querySelectorAll('.dropzone');
            let priorities;
            let dataColors = [
                @foreach($statuses as $id => $status)
                {
                    id:"{{$id}}",
                    title:"{{$status['name']}}",
                    color:"{{$status['color']}}",
                    only_recent:{{$status['only_recent']?1:0}},
                },
                @endforeach
            ];

            let dataCards = [];
            initializeBoards();
            //initializeCards();
            window.scrollTo({ top: 0});
            /*intitialize the boards in the panel*/
            function initializeBoards(){
                dataColors.forEach(item => {
                    let htmlString = `
                <div class="board" style="background-color:#ffffff">
                    <div style="width:100%;border-top: 6px solid ${item.color};margin-bottom: 10px;border-radius: 3px;background: #fff;box-shadow: 0 1px 2px 1px rgb(0 0 0 / 6%);">
                        <h3 class="text-center">${item.title} <span class="statusCount"></span><br><small style="font-size:9px">
`+((item.only_recent)?'{{__('Only items from the last 24h are displayed in this list')}}':'{{__('All items in this status are displayed in this list')}}')+`</small>
</h3>
                    </div>
                    <div class="dropzone" data-item="${item.id}" data-only-recent="${item.only_recent}" style="border: 2px dashed ${item.color};" data-color="${item.color}">
                        <i class="fa fa-spinner fa-spin" style="font-size: 24px"></i>
                    </div>
                </div>
            `;
                    $("#boardsContainer").append(htmlString);
                });
            }
            getOrders();
            setInterval(getOrders, 10000);


            function getOrders(){
                $('.dropzone').each(function(){
                    let $this = $(this);
                    let status = $(this).attr('data-item');
                    let color = $(this).attr('data-color');
                    let only_recent = $(this).attr('data-only-recent');
                    console.log(status);
                    $.ajax({
                        url: "{{route('admin.dashboard.ajaxGetOrders')}}",
                        type: "POST",
                        dataType: "json",
                        data:{
                            "status":status,
                            "only_recent":only_recent
                        },
                        success: function(data){
                            let html = '';
                            $.each(data,function(key, value) {
                                html += getOrderCard(value,color);
                            });
                            $this.html(html);
                            buildAndShowCardQtty();
                        }
                    });
                });

            }

            function buildAndShowCardQtty() {
                $('.board').each(function () {
                    $this = $(this);
                    let len = $this.find('.kanbanCard').length;
                    $this.find('.statusCount').html("(" + len + ")");
                });
            }


            function getOrderCard(card,status_color){
                return `
            <div id="`+card.id+`" class="kanbanCard" style="border-left: 4px solid `+status_color+`;">
                <div class="content">
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <th class="title">#`+card.id+`</th>
                                <th class="description">
                                    <i class="fas fa-calendar"></i> `+card.created_at+`<br>
                                    <i class="fas fa-motorcycle"></i> `+card.courier_name+`<br>
                                    <i class="fas fa-user"></i> `+card.customer_name+`<br>
                                    <i class="fas fa-home"></i> `+card.pickup_location+`<br>
                                    <i class="fas fa-map"></i> `+card.delivery_location+`<br>
                                    `+(card.return_location?'<i class="fas fa-check"></i> {{__('Return to pickup location')}}<br>':'')+`
                                    <i class="fas fa-map-marker"></i> `+card.distance+`{{setting('distance_unit','mi')}}<br>
                                </th>
                            </tr>
                        </thead>
                    </table>

                    <div class="orderPrices" style="padding: 5px 5px;">
                        {{trans('Courier Value')}}: `+card.app_value+`<br>
                        {{trans('App Fee')}}: `+card.courier_value+`<br>
                        {{trans('Total')}}: `+card.total+`
                    </div>
                    <div class="orderActions">
                        <a href="`+card.link+`" class='btn btn-block btn-outline-primary btn-sm' target="_blank">{{__('Open Order')}} <i class="fas fa-external-link"></i></a>
                    </div>
                </div>
            </div>
            `;
                $(`.dropzone[data-item="`+card.order_status+`"]`).append(htmlString);
                priorities = document.querySelectorAll(".priority");
            }

        });

        //last 6 months chart
        const labels = [
            @foreach($chart as $key => $value)
                '{{$key}}',
            @endforeach
        ];

        const data = {
            labels: labels,
            datasets: [{
                label: '{{__('Total (:currency)',['currency'=> getUtf8ConvertedStringFromHtmlEntities(getCurrencySymbolByCurrencyCode(setting('currency','USD')))])}}',
                backgroundColor: 'rgb(9,150,72)',
                borderColor: 'rgb(7,100,48)',
                data: [
                    @foreach($chart as $key => $value)
                        {{$value['revenue']}},
                    @endforeach
                ],
            },{
                label: '{{__('Orders Count')}}',
                backgroundColor: 'rgb(29,108,173)',
                borderColor: 'rgb(7,75,100)',
                data: [
                    @foreach($chart as $key => $value)
                        {{$value['count']}},
                    @endforeach
                ],
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {},
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );

    </script>
@endpush

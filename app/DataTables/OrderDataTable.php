<?php

namespace App\DataTables;

use App\Models\Order;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        return $dataTable
            ->editColumn('distance', function ($line) {
                return $line['distance'].' '.setting('distance_unit','mi');
            })
            ->editColumn('payment_gateway', function ($line) {
                if($line['offline_payment_method_id'] != 0){
                    return $line['offlinePaymentMethod']['name'];
                }
                return ucwords($line['payment_gateway']);
            })
            ->editColumn('order_status',function($line){
                return trans('general.order_status_list.'.$line['order_status']);
            })
            ->editColumn('created_at', function ($line) {
                return getDateHumanFormat($line['created_at'],true);
            })->addColumn('action', 'admin.orders.datatables_actions')
            ->rawColumns(['created_at','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        return $model->with(['offlinePaymentMethod','courier','courier.user'])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())

            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'order' => [[0, 'desc']],
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/'.app()->getLocale().'/datatable.json')
                        ),true),
                    'buttons' => [
                        'export',
                        'print',
                        'reset',
                        'reload',
                    ],
                ]
            ));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            [
                'data' => 'id',
                'title' => '#',
            ],
            [
                'data' => 'user.name',
                'title' => __('Customer'),
            ],
            [
                'data' => 'courier.user.name',
                'title' => __('Courier'),
            ],
            [
                'data' => 'distance',
                'title' => __('Distance'),
            ],
            [
                'data' => 'total_value',
                'title' => __('Price'),
            ],
            [
                'data' => 'payment_gateway',
                'title' => __('Payment'),
            ],
            [
                'data' => 'order_status',
                'title' => __('Order Status'),
            ],
            [
                'data' => 'created_at',
                'title' => __('Created At'),
            ],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'orders_datatable_' . time();
    }
}

<?php

namespace App\DataTables;

use App\Models\Courier;
use App\Models\CourierPayout;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class CourierPayoutSummaryDataTable extends DataTable
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

        return $dataTable
            ->editColumn('courier_value', function ($line) {
                return getPrice($line['courier_value']);
            })
            ->editColumn('app_value', function ($line) {
                return getPrice($line['app_value']);
            })
            ->editColumn('payout_amount', function ($line) {
                return getPrice($line['payout_amount']);
            })->rawColumns(['courier_value','app_value','payout_amount']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CourierPayout $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CourierPayout $model)
    {

        $query = Courier::selectRaw('
            couriers.id,
            users.name,
            (SELECT count(orders.id) FROM orders WHERE orders.courier_id = couriers.id and orders.payment_status = "paid") as orders_count,
            (SELECT SUM(orders.courier_value) FROM orders WHERE orders.courier_id = couriers.id and orders.payment_status = "paid") as courier_value,
            (SELECT SUM(orders.app_value) FROM orders WHERE orders.courier_id = couriers.id and orders.payment_status = "paid") as app_value,
            (SELECT SUM(courier_payouts.amount) FROM courier_payouts WHERE courier_payouts.courier_id = couriers.id) as payout_amount
            ')
            ->join('users','users.id','=','couriers.user_id');

        return DataTables::of($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('courier-payouts-summary-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.courierPayouts.courierSummary'))
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'order' => [[0, 'asc']],
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
                'data' => 'name',
                'title' => trans('Driver'),

            ],
            [
                'data' => 'orders_count',
                'title' => trans('Orders Count'),

            ],
            [
                'data' => 'courier_value',
                'title' => trans('Courier Total Value'),

            ],
            [
                'data' => 'app_value',
                'title' => trans('App Total Value'),

            ],
            [
                'data' => 'payout_amount',
                'title' => trans('Payout Total'),

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
        return 'courier_payouts_summary_datatable_' . time();
    }
}

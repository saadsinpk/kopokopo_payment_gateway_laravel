<?php

namespace App\DataTables;

use App\Models\CourierPayout;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class CourierPayoutDataTable extends DataTable
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
            ->editColumn('date', function ($line) {
                return getDateHumanFormat($line['date'],true);
            })
            ->editColumn('amount', function ($line) {
                return getPrice($line['amount']);
            })
            ->addColumn('action', 'admin.courier_payouts.datatables_actions')
            ->rawColumns(['action','amount','date']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CourierPayout $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CourierPayout $model)
    {
        return $model->with(['courier','courier.user'])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('courier-payouts-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.courierPayouts.courierTable'))
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/'.app()->getLocale().'/datatable.json')
                        ),true),
                    'order' => [[3, 'desc']],
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
                'data' => 'courier.user.name',
                'title' => trans('Driver'),

            ],
            [
                'data' => 'method',
                'title' => trans('Method'),

            ],
            [
                'data' => 'amount',
                'title' => trans('Amount'),

            ],
            [
                'data' => 'date',
                'title' => trans('Date'),

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
        return 'courier_payouts_datatable_' . time();
    }
}

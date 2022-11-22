<?php

namespace App\DataTables;

use App\Models\Courier;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class CourierDataTable extends DataTable
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
            ->editColumn('picture', function ($line) {
                return getMediaColumn($line['user']);
            })
            ->editColumn('active', function ($line){
                return getBoolColumn($line['active']);
            })
            ->editColumn('last_location_at',function($line){
                if($line['last_location_at'] == null){
                    return '-';
                }
                return getDateHumanFormat($line['last_location_at'],true);
            })
            ->editColumn('created_at',function($line){
                return getDateHumanFormat($line['created_at'],true);
            })
            ->addColumn('action', 'admin.couriers.datatables_actions')
            ->rawColumns(['picture','active','action','last_location_at','created_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Courier $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Courier $model)
    {
        return $model->with('user')->newQuery();
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
                    'order' => [[5, 'desc']],
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
                'data' => 'picture',
                'title' => trans('Picture'),
                'orderable' => false,
                'searchable' => false,
            ],
            [
                'data' => 'user.name',
                'title' => __('Name'),
            ],
            [
                'data' => 'user.phone',
                'title' => __('Phone'),
            ],
            [
                'data' => 'active',
                'title' => __('Active Now'),
            ],
            [
                'data' => 'last_location_at',
                'title' => __('Last Location At'),
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
        return 'couriers_datatable_' . time();
    }
}

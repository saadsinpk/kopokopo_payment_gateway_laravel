<?php

namespace App\DataTables;

use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class UserDataTable extends DataTable
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
            ->editColumn('picture', function ($line) {
                return getMediaColumn($line);
            })
            ->editColumn('created_at', function ($line) {
                return getDateHumanFormat($line['created_at'],true);
            })
            ->editColumn('email', function ($line) {
                return getLinkForEmail($line['email']);
            })
            ->editColumn('role', function ($line) {
                $rolesList = [];
                foreach ($line->roles as $role) {
                    $rolesList[] = $role->name;
                }
                return ucwords(implode(', ', $rolesList));
            })
            ->addColumn('action', 'admin.users.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()->with('roles');
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
            ->addAction(['title'=>trans('general.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/'.app()->getLocale().'/datatable.json')
                        ),true),
                    'order' => [[5, 'desc']],
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
        // TODO custom element generator
        $columns = [
            [
                'data' => 'picture',
                'title' => trans('Picture'),
                'orderable' => false,
                'searchable' => false,

            ],
            [
                'data' => 'name',
                'title' => trans('Name'),

            ],
            [
                'data' => 'email',
                'title' => trans('Email'),

            ],
            [
                'data' => 'phone',
                'title' => trans('Phone'),

            ],
            [
                'data' => 'role',
                'title' => trans('Role'),
                'orderable' => false,
                'searchable' => false,

            ],
            [
                'data' => 'created_at',
                'title' => trans('Created At'),
                'searchable' => false,
            ]
        ];

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users_datatable_' . time();
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }
}

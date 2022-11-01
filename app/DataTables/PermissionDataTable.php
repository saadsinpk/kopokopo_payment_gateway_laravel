<?php

namespace App\DataTables;

use App\Models\Permission;
use App\Models\Role;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PermissionDataTable extends DataTable
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
            ->editColumn('roles', function ($permission) {
                return json_encode(['permission_id' => $permission->id,'permission' => $permission->name, 'roles' => $permission->roles->pluck('name')->toArray()]);
            });
            //->addColumn('action', 'admin.permissions.datatables_actions')
            //->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Permission $model)
    {
        return $model->newQuery()->with('roles',function($query){
            return $query->whereNotIn('name',['client','driver']);
        });

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
            //->addAction(['title' => trans('general.actions'), 'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true),
                    'buttons' => [],
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
        $columns = [
            [
                'data' => 'name',
                'title' => trans('general.permission_name'),
                'searchable' => true
            ],
            [
                'data' => 'guard_name',
                'title' => trans('general.permission_guard_name'),
                'searchable' => false,
            ],
            [
                'data' => 'roles',
                'title' => trans('general.role_plural'),
                'visible' => false,
                'className' => "hide",
                'searchable' => false
            ],
        ];


        $roles = Role::select('id', 'name')->whereNotIn('name',['client','driver'])->get();
        foreach ($roles as $role) {
            $columns[] = [
                'data' => 'roles',
                'title' => $role->name,
                'searchable' => 'false',
                'exportable' => 'false',
                'printable' => 'false',
                'raw' => true,
                'render' => 'function(){let jsonData = JSON.parse(data.replace(/&quot;/g, \'\\"\'));
                    let checkText = "";
                    if(jsonData["roles"].includes("' . $role->name . '")){
                        return "<input  type=\'checkbox\' name=\'permissionCheck\' class=\'permission\' data-role-name=\'' . $role->name . '\' data-role-id=\'' . $role->id . '\' data-permission=\'"+data+"\' checked>";
                    }else{
                        return "<input  type=\'checkbox\' name=\'permissionCheck\' class=\'permission\' data-role-name=\'' . $role->name . '\' data-role-id=\'' . $role->id . '\' data-permission=\'"+data+"\' >";
                    }
                }',
            ];
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'permissionsdatatable_' . time();
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

<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PermissionDataTable;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PermissionRepository;
use Flash;
use Illuminate\Http\Request;
use Response;

class PermissionController extends AppBaseController
{
    /** @var PermissionRepository $permissionRepository*/
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepo)
    {
        $this->permissionRepository = $permissionRepo;
    }

    /**
     * Display a listing of the Permission.
     *
     * @param PermissionDataTable $permissionDataTable
     *
     * @return Response
     */
    public function index(PermissionDataTable $permissionDataTable)
    {
        return $permissionDataTable->render('admin.permissions.index');
    }

    /**
     * Update the specified Permission in storage.
     *
     * @param int $id
     * @return json
     */
    public function update(Request $request)
    {
        if (env('APP_DEMO', false)) {
            return response()->json(['success' => false, 'message'=> __('This is a demo version. You will not be able to make changes.')]);
        }
        $permissionJson = json_decode($request->permission_json_string);
        $input = [
            'roleId' => $request->get('roleId'),
            'permission' => $permissionJson->permission
        ];

        if($request->get('allow',false)){
            //give the permission
            $this->permissionRepository->givePermissionToRole($input);
        }else{
            //revoke the permission
            $this->permissionRepository->revokePermissionToRole($input);
        }

        return response()->json(['success' => true]);
    }

}

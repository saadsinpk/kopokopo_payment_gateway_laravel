<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\RoleRepository;
use Flash;
use Response;

class RoleController extends AppBaseController
{
    /** @var RoleRepository $roleRepository*/
    private $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
    }

    /**
     * Display a listing of the Role.
     *
     * @param RoleDataTable $roleDataTable
     *
     * @return Response
     */
    public function index(RoleDataTable $roleDataTable)
    {
        return $roleDataTable->render('admin.roles.index');
    }

    /**
     * Show the form for creating a new Role.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param CreateRoleRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }
        $input = $request->all();

        $role = $this->roleRepository->create($input);

        Flash::success('Role saved successfully.');

        return redirect(route('admin.roles.index'));
    }



    /**
     * Show the form for editing the specified Role.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if(in_array($id,[1,2,3])){
            Flash::error(__('You are not authorized to edit this role.'));
            return redirect(route('admin.roles.index'));
        }
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            Flash::error('Role not found');

            return redirect(route('admin.roles.index'));
        }

        return view('admin.roles.edit')->with('role', $role);
    }

    /**
     * Update the specified Role in storage.
     *
     * @param int $id
     * @param UpdateRoleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }
        if(in_array($id,[1,2,3])){
            Flash::error(__('You are not authorized to edit this role.'));
            return redirect(route('admin.roles.index'));
        }
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            Flash::error('Role not found');

            return redirect(route('admin.roles.index'));
        }

        $role = $this->roleRepository->update($request->all(), $id);

        Flash::success('Role updated successfully.');

        return redirect(route('admin.roles.index'));
    }

    /**
     * Remove the specified Role from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if(in_array($id,[1,2,3])){
            Flash::error(__('You are not authorized to edit this role.'));
            return redirect(route('admin.roles.index'));
        }
        $role = $this->roleRepository->find($id);

        if (empty($role)) {
            Flash::error('Role not found');

            return redirect(route('admin.roles.index'));
        }

        $this->roleRepository->delete($id);

        Flash::success('Role deleted successfully.');

        return redirect(route('admin.roles.index'));
    }
}

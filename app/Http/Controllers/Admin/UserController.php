<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Courier;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Support\Facades\Hash;
use Response;

class UserController extends AppBaseController
{
    /** @var UserRepository $userRepository*/
    private $userRepository;
    /** @var RoleRepository $roleRepository */
    private $roleRepository;

    public function __construct(UserRepository $userRepo,RoleRepository $roleRepo)
    {
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
    }

    /**
     * Return a list of the User as JSON.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexJson(Request $request)
    {
        if(!$request->ajax()){
            abort(404);
        }
        try{
            $this->userRepository->pushCriteria(new RequestCriteria($request));
            $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));

            $couriers = $this->userRepository->select('users.id', 'users.name')->get();
            
            return $this->sendResponse($couriers, 'Products retrieved successfully');

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     *
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render('admin.users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $roles = $this->roleRepository->pluck('name', 'id');
        return view('admin.users.create')->with('roles',$roles);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);
        if ($request->has('image') && !is_null($request->file('image'))) {
            //upload and associate
            $fileName = uniqid($user->id . "-") . "." . $request->file('image')->getClientOriginalExtension();
            $storeFile = $request->file('image')->storeAs('images', $fileName);

            if ($storeFile == false) {
                $user->delete();
                Flash::error(__('An error occurred uploading the image. Please try again.'));
                return redirect()->back();
            }

            $user->addMedia($request->file('image'))->toMediaCollection('default');
        }

        $user->syncRoles($request->get('roles'));

        if($user->hasRole('driver')){
            Courier::firstOrCreate(['user_id' => $user->id],[
                'user_id' => $user->id,
                'active' => false,
                'using_app_pricing' => 1
            ]);
        }

        Flash::success('User saved successfully.');

        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('admin.users.index'));
        }

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        $roles = $this->roleRepository->pluck('name', 'id');
        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('admin.users.index'));
        }

        return view('admin.users.edit')->with('user', $user)->with('roles',$roles);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('admin.users.index'));
        }

        $requests = $request->all();
        if($request->get('password')){
            $requests['password'] = Hash::make($request->get('password'));
        }else{
            $requests['password'] = $user->password;
        }

        $user = $this->userRepository->update($requests, $id);
        if ($request->has('image') && !is_null($request->file('image'))) {
            //upload and associate

            $fileName = uniqid($user->id . "-") . "." . $request->file('image')->getClientOriginalExtension();
            $storeFile = $request->file('image')->storeAs('images', $fileName);

            if ($storeFile == false) {

                Flash::error(__('An error occurred uploading the image. Please try again.'));
                return redirect()->back();
            }
            $user->clearMediaCollection('default');
            $user->addMedia($request->file('image'))->toMediaCollection('default');
        }
        $user->syncRoles($request->get('roles'));
        if($user->hasRole('driver')){
            Courier::firstOrCreate(['user_id' => $user->id],[
                'user_id' => $user->id,
                'active' => false,
                'using_app_pricing' => 1
            ]);
        }
        Flash::success('User updated successfully.');

        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('admin.users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('admin.users.index'));
    }
}

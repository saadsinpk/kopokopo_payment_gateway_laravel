<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourierDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCourierRequest;
use App\Http\Requests\UpdateCourierRequest;
use App\Repositories\CourierRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Response;

class CourierController extends AppBaseController
{
    /** @var CourierRepository $courierRepository*/
    private $courierRepository;

    public function __construct(CourierRepository $courierRepo)
    {
        $this->courierRepository = $courierRepo;
    }

    /**
     * Return a list of the Courier as JSON.
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
            $this->courierRepository->pushCriteria(new RequestCriteria($request));
            $this->courierRepository->pushCriteria(new LimitOffsetCriteria($request));

            $couriers = $this->courierRepository->join('users','users.id','=','couriers.user_id')->select('couriers.id', 'users.name')->get();
            
            return $this->sendResponse($couriers, 'Products retrieved successfully');

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

    }

    /**
     * Display a listing of the Courier.
     *
     * @param CourierDataTable $courierDataTable
     *
     * @return Response
     */
    public function index(CourierDataTable $courierDataTable)
    {
        return $courierDataTable->render('admin.couriers.index');
    }

    /**
     * Show the form for creating a new Courier.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.couriers.create');
    }

    /**
     * Store a newly created Courier in storage.
     *
     * @param CreateCourierRequest $request
     *
     * @return Response
     */
    public function store(CreateCourierRequest $request)
    {
        $input = $request->all();

        $courier = $this->courierRepository->create($input);

        Flash::success('Courier saved successfully.');

        return redirect(route('admin.couriers.index'));
    }

    /**
     * Display the specified Courier.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $courier = $this->courierRepository->with(['user'])->find($id);

        if (empty($courier)) {
            Flash::error('Courier not found');

            return redirect(route('admin.couriers.index'));
        }

        return view('admin.couriers.show')->with('courier', $courier);
    }

    /**
     * Show the form for editing the specified Courier.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $courier = $this->courierRepository->find($id);

        if (empty($courier)) {
            Flash::error('Courier not found');

            return redirect(route('admin.couriers.index'));
        }

        return view('admin.couriers.edit')->with('courier', $courier);
    }

    /**
     * Update the specified Courier in storage.
     *
     * @param int $id
     * @param UpdateCourierRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCourierRequest $request)
    {
        $courier = $this->courierRepository->find($id);

        if (empty($courier)) {
            Flash::error('Courier not found');

            return redirect(route('admin.couriers.index'));
        }

        $courier = $this->courierRepository->update($request->all(), $id);

        Flash::success('Courier updated successfully.');

        return redirect(route('admin.couriers.index'));
    }

    /**
     * Remove the specified Courier from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $courier = $this->courierRepository->find($id);

        if (empty($courier)) {
            Flash::error('Courier not found');

            return redirect(route('admin.couriers.index'));
        }

        $this->courierRepository->delete($id);

        Flash::success('Courier deleted successfully.');

        return redirect(route('admin.couriers.index'));
    }
}

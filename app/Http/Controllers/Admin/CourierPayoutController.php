<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourierPayoutDataTable;
use App\DataTables\CourierPayoutSummaryDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCourierPayoutRequest;
use App\Http\Requests\UpdateCourierPayoutRequest;
use App\Models\Courier;
use App\Repositories\CourierPayoutRepository;
use Flash;
use Response;

class CourierPayoutController extends AppBaseController
{
    /** @var CourierPayoutRepository $courierPayoutRepository*/
    private $courierPayoutRepository;

    /** @var Courier $courierRepository */
    private $courierRepository;

    public function __construct(CourierPayoutRepository $courierPayoutRepo, Courier $courierRepo)
    {
        $this->courierPayoutRepository = $courierPayoutRepo;
        $this->courierRepository = $courierRepo;
    }

    /**
     * Display a listing of the CourierPayout.
     *
     * @param CourierPayoutDataTable $courierPayoutDataTable
     *
     * @return Response
     */
    public function index(CourierPayoutDataTable $courierPayoutDataTable, CourierPayoutSummaryDataTable $courierPayoutSummaryDataTable)
    {
        return view('admin.courier_payouts.index',[
            'courierPayoutDataTable' => $courierPayoutDataTable->html(),
            'courierPayoutSummaryDataTable' => $courierPayoutSummaryDataTable->html()
        ]);
    }

    /*
     * Interact with datatable request for courier payout
     * @param  CourierPayoutDataTable $courierPayoutDataTable
     * @return Response
     */
    public function getCourierPayoutDataTable(CourierPayoutDataTable $courierPayoutDataTable){
        return $courierPayoutDataTable->render('admin.courier_payouts.index');
    }

    /*
     * Interact with datatable request for courier payout
     * @param  CourierPayoutSummaryDataTable $courierPayoutSummaryDataTable
     * @return Response
     */
    public function getCourierPayoutSummaryDataTable(CourierPayoutSummaryDataTable $courierPayoutSummaryDataTable){
        return $courierPayoutSummaryDataTable->render('admin.courier_payouts.index');
    }

    /**
     * Show the form for creating a new CourierPayout.
     *
     * @return Response
     */
    public function create()
    {
        $couriers = $this->courierRepository->join('users','users.id','=', 'couriers.user_id')->orderBy('users.name')->pluck('users.name', 'couriers.id');
        return view('admin.courier_payouts.create')->with('couriers', $couriers);
    }

    /**
     * Store a newly created CourierPayout in storage.
     *
     * @param CreateCourierPayoutRequest $request
     *
     * @return Response
     */
    public function store(CreateCourierPayoutRequest $request)
    {
        $input = $request->all();

        $courierPayout = $this->courierPayoutRepository->create($input);

        Flash::success('Courier Payout saved successfully.');

        return redirect(route('admin.courierPayouts.index'));
    }

    /**
     * Display the specified CourierPayout.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $courierPayout = $this->courierPayoutRepository->find($id);

        if (empty($courierPayout)) {
            Flash::error('Courier Payout not found');

            return redirect(route('admin.courierPayouts.index'));
        }

        return view('admin.courier_payouts.show')->with('courierPayout', $courierPayout);
    }

    /**
     * Show the form for editing the specified CourierPayout.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $courierPayout = $this->courierPayoutRepository->find($id);

        if (empty($courierPayout)) {
            Flash::error('Courier Payout not found');

            return redirect(route('admin.courierPayouts.index'));
        }

        return view('admin.courier_payouts.edit')->with('courierPayout', $courierPayout);
    }

    /**
     * Update the specified CourierPayout in storage.
     *
     * @param int $id
     * @param UpdateCourierPayoutRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCourierPayoutRequest $request)
    {
        $courierPayout = $this->courierPayoutRepository->find($id);

        if (empty($courierPayout)) {
            Flash::error('Courier Payout not found');

            return redirect(route('admin.courierPayouts.index'));
        }

        $courierPayout = $this->courierPayoutRepository->update($request->all(), $id);

        Flash::success('Courier Payout updated successfully.');

        return redirect(route('admin.courierPayouts.index'));
    }

    /**
     * Remove the specified CourierPayout from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $courierPayout = $this->courierPayoutRepository->find($id);

        if (empty($courierPayout)) {
            Flash::error('Courier Payout not found');

            return redirect(route('admin.courierPayouts.index'));
        }

        $this->courierPayoutRepository->delete($id);

        Flash::success('Courier Payout deleted successfully.');

        return redirect(route('admin.courierPayouts.index'));
    }
}

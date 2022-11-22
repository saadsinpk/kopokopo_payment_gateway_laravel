<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OfflinePaymentMethodDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateOfflinePaymentMethodRequest;
use App\Http\Requests\UpdateOfflinePaymentMethodRequest;
use App\Repositories\OfflinePaymentMethodRepository;
use Flash;
use Response;

class OfflinePaymentMethodController extends AppBaseController
{
    /** @var OfflinePaymentMethodRepository $offlinePaymentMethodRepository*/
    private $offlinePaymentMethodRepository;

    public function __construct(OfflinePaymentMethodRepository $offlinePaymentMethodRepo)
    {
        $this->offlinePaymentMethodRepository = $offlinePaymentMethodRepo;
    }

    /**
     * Display a listing of the OfflinePaymentMethod.
     *
     * @param OfflinePaymentMethodDataTable $offlinePaymentMethodDataTable
     *
     * @return Response
     */
    public function index(OfflinePaymentMethodDataTable $offlinePaymentMethodDataTable)
    {
        return $offlinePaymentMethodDataTable->render('admin.offline_payment_methods.index');
    }

    /**
     * Show the form for creating a new OfflinePaymentMethod.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.offline_payment_methods.create');
    }

    /**
     * Store a newly created OfflinePaymentMethod in storage.
     *
     * @param CreateOfflinePaymentMethodRequest $request
     *
     * @return Response
     */
    public function store(CreateOfflinePaymentMethodRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }
        $input = $request->all();

        $offlinePaymentMethod = $this->offlinePaymentMethodRepository->create($input);

        if ($request->has('image') && !is_null($request->file('image'))) {
            //upload and associate

            $fileName = uniqid($offlinePaymentMethod->id . "-") . "." . $request->file('image')->getClientOriginalExtension();
            $storeFile = $request->file('image')->storeAs('images', $fileName);

            if ($storeFile == false) {
                $offlinePaymentMethod->delete();
                Flash::error(__('An error occurred uploading the image. Please try again.'));
                return redirect()->back();
            }

            $offlinePaymentMethod->addMedia($request->file('image'))->toMediaCollection('default');
        }

        Flash::success('Offline Payment Method saved successfully.');

        return redirect(route('admin.offlinePaymentMethods.index'));
    }

    /**
     * Display the specified OfflinePaymentMethod.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $offlinePaymentMethod = $this->offlinePaymentMethodRepository->find($id);

        if (empty($offlinePaymentMethod)) {
            Flash::error('Offline Payment Method not found');

            return redirect(route('admin.offlinePaymentMethods.index'));
        }

        return view('admin.offline_payment_methods.show')->with('offlinePaymentMethod', $offlinePaymentMethod);
    }

    /**
     * Show the form for editing the specified OfflinePaymentMethod.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $offlinePaymentMethod = $this->offlinePaymentMethodRepository->find($id);

        if (empty($offlinePaymentMethod)) {
            Flash::error('Offline Payment Method not found');

            return redirect(route('admin.offlinePaymentMethods.index'));
        }

        return view('admin.offline_payment_methods.edit')->with('offlinePaymentMethod', $offlinePaymentMethod);
    }

    /**
     * Update the specified OfflinePaymentMethod in storage.
     *
     * @param int $id
     * @param UpdateOfflinePaymentMethodRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOfflinePaymentMethodRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }
        $offlinePaymentMethod = $this->offlinePaymentMethodRepository->find($id);

        if (empty($offlinePaymentMethod)) {
            Flash::error('Offline Payment Method not found');

            return redirect(route('admin.offlinePaymentMethods.index'));
        }

        $offlinePaymentMethod = $this->offlinePaymentMethodRepository->update($request->all(), $id);

        if ($request->has('image') && !is_null($request->file('image'))) {
            //upload and associate

            $fileName = uniqid($offlinePaymentMethod->id . "-") . "." . $request->file('image')->getClientOriginalExtension();
            $storeFile = $request->file('image')->storeAs('images', $fileName);

            if ($storeFile == false) {

                Flash::error(__('An error occurred uploading the image. Please try again.'));
                return redirect()->back();
            }
            $offlinePaymentMethod->clearMediaCollection('default');
            $offlinePaymentMethod->addMedia($request->file('image'))->toMediaCollection('default');
        }

        Flash::success('Offline Payment Method updated successfully.');

        return redirect(route('admin.offlinePaymentMethods.index'));
    }

    /**
     * Remove the specified OfflinePaymentMethod from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $offlinePaymentMethod = $this->offlinePaymentMethodRepository->find($id);

        if (empty($offlinePaymentMethod)) {
            Flash::error('Offline Payment Method not found');

            return redirect(route('admin.offlinePaymentMethods.index'));
        }

        $this->offlinePaymentMethodRepository->delete($id);

        Flash::success('Offline Payment Method deleted successfully.');

        return redirect(route('admin.offlinePaymentMethods.index'));
    }
}

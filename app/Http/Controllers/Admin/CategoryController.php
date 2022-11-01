<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use Flash;
use Response;

class CategoryController extends AppBaseController
{
    /** @var CategoryRepository $categoryRepository*/
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     *
     * @return Response
     */
    public function index(CategoryDataTable $categoryDataTable)
    {
        return $categoryDataTable->render('admin.categories.index');
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }

        $input = $request->all();

        $category = $this->categoryRepository->create($input);

        if ($request->has('image') && !is_null($request->file('image'))) {
            //upload and associate

            $fileName = uniqid($category->id . "-") . "." . $request->file('image')->getClientOriginalExtension();
            $storeFile = $request->file('image')->storeAs('images', $fileName);

            if ($storeFile == false) {
                $category->delete();
                Flash::error(__('An error occurred uploading the image. Please try again.'));
                return redirect()->back();
            }

            $category->addMedia($request->file('image'))->toMediaCollection('default');
        }

        Flash::success('Category saved successfully.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        return view('admin.categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        return view('admin.categories.edit')->with('category', $category);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param int $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }

        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        $category = $this->categoryRepository->update($request->all(), $id);

        if ($request->has('image') && !is_null($request->file('image'))) {
            //upload and associate

            $fileName = uniqid($category->id . "-") . "." . $request->file('image')->getClientOriginalExtension();
            $storeFile = $request->file('image')->storeAs('images', $fileName);

            if ($storeFile == false) {

                Flash::error(__('An error occurred uploading the image. Please try again.'));
                return redirect()->back();
            }
            $category->clearMediaCollection('default');
            $category->addMedia($request->file('image'))->toMediaCollection('default');
        }


        Flash::success('Category updated successfully.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Remove the specified Category from storage.
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
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        $this->categoryRepository->delete($id);

        Flash::success('Category deleted successfully.');

        return redirect(route('admin.categories.index'));
    }
}

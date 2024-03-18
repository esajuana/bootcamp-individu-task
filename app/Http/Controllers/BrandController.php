<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Traits\JsonResponseTrait;

class BrandController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display  a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();

        return $this->showResponse($brands->toArray());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandStoreRequest $request)
    {
        if ($request->validated()) {
            $brand = Brand::create($request->all());

            return $this->createdResponse($brand->toArray());
        } else {
            return $this->validationErrorResponse($request->errors());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $brand = Brand::findOrFail($id);

        return $this->showResponse($brand->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandUpdateRequest $request, $id)
    {
        if ($request->validated()) {
            $brand = Brand::findOrFail($id);
            $brand->update($request->all());

            return $this->updatedResponse($brand->toArray());
        } else {
            return $this->validationErrorResponse($request->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return $this->deletedResponse($brand->toArray());
    }

    /**
     * Display the removed data
     */
    public function trash()
    {
        $brands = Brand::onlyTrashed()->get();

        return $this->showResponse($brands->toArray());
    }

    /**
     * Restore the specified resource
     */
    public function restore($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->restore();

        return $this->restoredResponse($brand->toArray());
    }
}

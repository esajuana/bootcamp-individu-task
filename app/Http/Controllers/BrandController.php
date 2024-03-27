<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Traits\JsonResponseTrait;
use App\Http\Resources\BrandResource;


class BrandController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display  a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();

        return BrandResource::collection($brands);
    
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandStoreRequest $request)
    {
        $validated = $request->validated();
        $brand = Brand::create($validated);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos');
            $brand->addImage($photoPath);
        }

        return response()->json(['message' => 'Brand created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $brand = Brand::with('image')->find($id);

        if ($brand) {
            return BrandResource::make($brand)->withDetail();
        } else {
            return response()->json(['message' => 'Brand  tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['message' => 'Brand  tidak ditemukan'], 404);
        }
        $brand->update($validated);
        $brand->updateImage($request);

        return response()->json(['message' => 'Brand  berhasil diupdate', 'brand' => $brand]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = brand::findOrFail($id);
        $brand->delete();

        return response()->json(['message' => 'Brand  berhasil dihapus']);
    }

    /**
     * Display the removed data
     */
    public function trash()
    {
        $brands = Brand::onlyTrashed()->get();
        // return $this->showResponse($brands->toArray());
        return response()->json(['message'=> 'Data berhasil diambil','brand'=> $brands]);
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

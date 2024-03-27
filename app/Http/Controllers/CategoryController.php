<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Image;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use App\Traits\JsonResponseTrait;

class CategoryController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->where('name', 'like', '%i%');
        }])->get();

        return $this->showResponse($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $validated = $request->validated();
        $category = Category::create($validated);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos');
            $category->addImage($photoPath);
        }

        return response()->json(['message' => 'category created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::with('image')->find($id);

        if ($category) {
            return CategoryResource::make($category)->withDetail();
        } else {
            return response()->json(['message' => 'Category tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request,  $id): JsonResponse
    {
        $validated = $request->validated();
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'category tidak ditemukan'], 404);
        }

        $category->update($validated);
        $category->updateImage($request);

        return response()->json(['message' => 'Category berhasil diupdate', 'category' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

     /**
     * Display the removed data
     */
    public function trash()
    {
        $categories = Category::onlyTrashed()->get();
        return $this->showResponse($categories);
    }

    public function restore($id) 
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        return response()->json(['message' => 'Produk berhasil dipulihkan.']);
    }
}

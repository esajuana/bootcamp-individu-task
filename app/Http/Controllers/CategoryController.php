<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
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
        if ($request->validated()) {
            $category = Category::create($request->all());
            return $this->createdResponse($category->toArray());
        } else {
            return $this->validationErrorResponse($request->errors());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
       
        return $this->showResponse($category->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request,  $id)
    {
        if ($request->validated()) {
            $category = Category::findOrFail($id);
            $category->update($request->all());
            return $this->updatedResponse($category->toArray());
        } else {
            return $this->validationErrorResponse($request->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return $this->deletedResponse($category->toArray());
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
        return $this->restoredResponse($category->toArray());
    }
}

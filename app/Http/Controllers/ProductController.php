<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Traits\JsonResponseTrait;

class ProductController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('category', 'brand');

        // search mechanism
        if ($request->has('search') && $request->search !== '') {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
        }

        // sort mechanism. Allows sorting by name, and price in ascending or descending order
        if ($request->has('sort')) {
            $sortField = $request->input('sort', 'name');  // default is to sort by name
            $sortDirection = $request->input('direction', 'asc');  // default is to sort in ascending order
            $products = $products->orderBy($sortField, $sortDirection);
        }

        // filter mechanism. Allows filtering by category id and price range
        // filter by category id
        if ($request->has('category_id')) {
            $products = $products->where('category_id', $request->category_id);
        }

        // filter by brand
        if ($request->has('brand_id')) {
            $products = $products->where('brand_id', $request->brand_id);
        }

        // filter by price range
        if ($request->has('price_min') && $request->has('price_max')) {
            $products = $products->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        // pagination mechanism
        $products = $products->paginate(10);   // get 10 products per page

        return $this->showResponse($products->toArray());
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        if ($request->validated()) {
            $product = Product::create($request->all());
            return $this->createdResponse($product->toArray());
        } else {
            return $this->validationErrorResponse($request->errors());
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $product->category_id = $product->category->name;
        $product->brand_id = $product->brand->name;
        return $this->showResponse($product->toArray());

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        if ($request->validated()) {
            $product = Product::findOrFail($id);

            $product->update($request->all());
            return $this->updatedResponse($product->toArray());
        } else {
            return $this->validationErrorResponse($request->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->notFoundResponse();
        }

        $product->delete();
        return $this->deletedResponse($product->toArray());
    }

     /**
     * get all products that are deleted
     */
    public function getSoftDelete()
    {
        $softDeletedProducts = Product::onlyTrashed()->get();

        if ($softDeletedProducts->isEmpty()) {
            return $this->notFoundResponse();
        }

        return $this->showResponse($softDeletedProducts);
    }


     /**
     * restore all products that are deleted
     */
    public function restore($id) 
    {
        $product = Product::withTrashed()->find($id);

        try {
            if ($product->restore()) {
                return $this->restoredResponse($product->toArray());
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}

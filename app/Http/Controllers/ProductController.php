<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        $products = Product::with('category', 'brand', 'images', 'user');

        // check if the user is authenticated
        if ($user) {
            $products = $products->where('user_id', $user->id);
        }

         // check if the user is searching for a product
         if ($request->has('search')) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
        }

         // check if the user is filtering by brand
         if ($request->has('brand')) {
            $products = $products->where('brand_id', $request->brand);
        }

        // check if the user is filtering by price range
        if ($request->has('min_price') && $request->has('max_price')) {
            $products = $products->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // check if the user is sorting the products by name, and price in ascending or descending
        if ($request->has('sort_by')) {
            $sortOrder = $request->has('sort_order') ? $request->sort_order : 'asc';
            $products = $products->orderBy($request->sort_by, $sortOrder);
        }

        // pagination mechanism
        $products = $products->paginate(10);   

       return ProductResource::collection($products);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();
        $product = Product::create($validated);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos');
            $product->addImage($photoPath);
        }

        return response()->json(['message' => 'Produk created successfully'], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('image')->find($id);
        if ($product) {
            return ProductResource::make($product)->withDetail();
        } else {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product tidak ditemukan'], 404);
        }
        if ($product->user_id !== auth()->id()) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk memperbarui produk ini'], 403);
        }
        $product->update($validated);
        $product->updateImage($request);

        return response()->json(['message' => 'Produk berhasil diupdate', 'produk' => $product]);
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

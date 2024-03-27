<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
{
    $data = [
        'id' => $this->id,
        'Name' => $this->name,
        'Price' => $this->price,
        'Stock' => $this->stock,
        'Brand Name' => $this->brand->name,
        'Category Name' => $this->category->name,
        'User Id' => $this->user_id,
        'User Name' => $this->user->name,
    ];

    // Adding conditional for photo
    if ($this->image) {
        $data['photo'] = $this->image->url;
    } else {
        $data['photo'] = null;
    }

    // // Adding additional details if isDetail is set to true
    // if ($this->isDetail) {
    //     $data['Description'] = $this->description;
    //     $data['Stock'] = $this->stock;
    // }

    return $data;
}

    public function withDetail()
    {
        $this->resource->isDetail = true;
        return $this;
    }

    public function with($request)
    {
        // Memastikan bahwa relasi images dimuat jika isDetail disetel ke true
        if ($this->resource->isDetail && !$this->resource->relationLoaded('image')) {
            $this->resource->load('image');
        }

        return parent::with($request);
    }
}
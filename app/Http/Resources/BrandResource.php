<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'name' => $this->name,
        ];

        if ($this->isDetail) {
            if ($this->image) {
                $data['photo'] = $this->image->url;
            } else {
                $data['photo'] = null;
            }
        }

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
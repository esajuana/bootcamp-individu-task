<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{

    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        // Pastikan isDetail disetel ke true dan relasi images dimuat
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
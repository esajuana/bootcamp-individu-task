<?php
namespace App\Traits;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;
trait UploadImageTrait
{
    public function addImage($photoPath)
    {
        $image = Image::create([
            'url' => $photoPath,
            'imageable_id' => $this->id,
            'imageable_type' => static::class,
        ]);

        return $image;
    }
    public function updateImage($request)
    {
        // Menghapus gambar yang ada
        foreach ($this->images as $image) {
            Storage::delete($image->url);
            $image->delete();
        }

        // Menyimpan gambar baru
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos');
            $this->addImage($photoPath);
        }
    }
}
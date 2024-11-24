<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

trait ImageUpload
{
    /**
     * Upload image with resizing store to Storage
     * @param Request $request
     * @param Request $folder
     * @param Request $with_size
     * @return null|string
     */
    public function upload(Request $request, $folder, $name = 'image', $width_size = 720): ?string
    {
        ini_set('upload_max_size', '100M');
        ini_set('post_max_size', '100M');
        ini_set('max_execution_time', 100000);

        try {
            $path = @$request->file($name)->hashName($folder);

            $img = ImageManager::gd()->read(@$request->file($name));
            $img->scale(width: $width_size);

            Storage::put($path, (string) $img->encode());
            $path_image = $path;

            return $path_image;
        } catch (\Throwable $th) {
            return null;
        }
    }
}

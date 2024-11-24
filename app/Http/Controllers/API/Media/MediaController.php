<?php

namespace App\Http\Controllers\API\Media;

use App\Http\Controllers\Controller;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    use ImageUpload;

    /**
     * Store File
     */
    public function storeFile(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $image = null;
        if ($request->hasFile('file')) {
            $image = $this->upload($request, 'images', 'file');
        }

        return response()->json([
            "message" => "File successfully uploaded",
            "data" => [
                "url" => url('storage') . '/' . $image,
            ]
        ], 200);
    }
}

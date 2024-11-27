<?php

namespace App\Http\Controllers\API\Educations;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * List Educations
     */
    public function index(Request $request)
    {
        $results = [];
        $educations = Education::latest()->get();

        foreach ($educations as $item) {
            $results[] = [
                "id" => $item->id,
                "title" => $item->title,
                "banner" => $item->banner  ? url('storage').'/'.@$item->banner : null,
                "contents" => $item->contents,
                "created_at" => $item->created_at->format("Y-m-d h:i:s")
            ];
        }

        return response()->json([
            "message" => "Education data successfully retrieved",
            "data" => $results
        ], 200);
    }
}

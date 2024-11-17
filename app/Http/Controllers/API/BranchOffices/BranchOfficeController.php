<?php

namespace App\Http\Controllers\API\BranchOffices;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use Illuminate\Http\Request;

class BranchOfficeController extends Controller
{

    /**
     * List Branch Offices
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $data = BranchOffice::orderBy('name', 'asc')->paginate($request->limit);
        $results = [];

        foreach ($data as $item) {
            $results[] = [
                "id" => $item->id,
                "name" => $item->name,
                "district" => @$item->district ? [
                    "id" => $item->district->id,
                    "name" => $item->district->name,
                ] : null,
                "address" => $item->address,
                "latitude" => $item->latitude,
                "longitude" => $item->longitude
            ];
        }

        return response()->json([
            "message" => "Branch offices data successfully retrieved",
            "data" => $results
        ], 200);
    }
}

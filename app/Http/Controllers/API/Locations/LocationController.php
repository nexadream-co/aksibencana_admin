<?php

namespace App\Http\Controllers\API\Locations;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    /**
     * List Locations
     */
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['string'],
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $data = [];
        $search = $request->q;
        $limit = $request->limit;

        $districts = District::with(['city']);

        if ($search) {
            foreach (explode(",", $search) as $key => $item) {
                if ($key == 0) {
                    $districts->where('name', 'LIKE', '%' . $item . '%');
                } else {
                    $districts->orWhere('name', 'LIKE', '%' . $item . '%');
                }
                $districts->orWhereHas('city', function ($query) use ($item) {
                    $query->where('name', 'LIKE', '%' . $item . '%');
                });
            }
        }

        $districts = $districts->paginate($limit ?? 10);
        foreach ($districts as $item) {
            $data[] = [
                "id" => $item->id,
                "name" => "$item->name",
                "city" => [
                    "id" => @$item->city->id,
                    "name" => @$item->city->name,
                ],
                "province" => [
                    "id" => @$item->city->province->id,
                    "name" => @$item->city->province->name,
                ],
            ];
        }

        return response()->json([
            "message" => "Location data successfully retrieved",
            "data" => $data,
        ], 200);
    }

    public function getCURL($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }

    /**
     * Run first time when init location data
     * ignore when you using import database directly
     */
    public function storeLocation()
    {
        ini_set('max_execution_time', 100000);
        $response = $this->getCURL(
            'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json'
        );

        DB::beginTransaction();
        Province::truncate();
        City::truncate();
        District::truncate();

        foreach ($response as $row) {
            $data = Province::find($row['id']);
            if (!$data) {
                Province::create([
                    'id' => $row['id'],
                    'name' => $row['name'],
                ]);
                $this->storeCity($row['id']);
            }
        }
        DB::commit();

        return response()->json(
            [
                'message' => 'Data Location successfully stored',
            ],
            200
        );
    }

    public function storeCity($id)
    {
        $response = $this->getCURL(
            "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/$id.json"
        );

        foreach ($response as $row) {
            $data = City::find($row['id']);
            if (!$data) {
                City::create([
                    'id' => $row['id'],
                    'province_id' => $id,
                    'name' => $row['name'],
                ]);

                $this->storeDistrict($row['id']);
            }
        }
    }

    public function storeDistrict($id)
    {
        $response = $this->getCURL(
            "https://www.emsifa.com/api-wilayah-indonesia/api/districts/$id.json"
        );

        foreach ($response as $row) {
            $data = District::find($row['id']);
            if (!$data) {
                District::create([
                    'id' => $row['id'],
                    'city_id' => $id,
                    'name' => $row['name'],
                ]);
            }
        }
    }
}

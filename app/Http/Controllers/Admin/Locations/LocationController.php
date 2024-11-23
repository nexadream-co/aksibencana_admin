<?php

namespace App\Http\Controllers\Admin\Locations;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Search location
     *
     * @return \Illuminate\Http\Response
     */
    public function searchDistricts(Request $request): array
    {
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
                "value" => "$item->name, {$item->city->name}, {$item->city->province->name}"
            ];
        }

        return $data;
    }
}

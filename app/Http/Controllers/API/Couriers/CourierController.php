<?php

namespace App\Http\Controllers\API\Couriers;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    /**
     * List Assignments
     */
    public function assignments(Request $request)
    {
        $request->validate([
            'status' => ['string'],
            'page' => ['string'],
            'limit' => ['string'],
        ]);

        $data = [];
        $assignments = Delivery::query();

        if ($request->status) {
            $assignments = $assignments->where('status', $request->status);
        }

        $assignments = $assignments->paginate($request->limit ?? 10);

        foreach ($assignments as $item) {

            $images = [];
            foreach (@json_decode(@$item->disaster->images) ?? [] as $row) {
                $images[] = url('storage') . '/' . $row;
            }

            $data[] = [
                "id" => $item->id,
                "status" => $item->status,
                "disaster" => @$item->disaster ? [
                    "id" => @$item->disaster->id,
                    "title" => @$item->disaster->title,
                    "description" => @$item->disaster->description,
                    "latitude" => @$item->station->latitude ?? @$item->disaster->latitude,
                    "longitude" => @$item->station->longitude ?? @$item->disaster->longitude,
                    "images" => $images
                ] : null,
            ];
        }

        return response()->json([
            "message" => "Assignment data successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * Detail Assignment
     */
    public function detailAssignment(string $id)
    {
        $assignment = Delivery::find($id);

        if (!@$assignment) {
            return response()->json([
                "message" => "Assignment not found"
            ], 404);
        }

        $images = [];
        foreach (@json_decode(@$assignment->disaster->images) ?? [] as $row) {
            $images[] = url('storage') . '/' . $row;
        }

        $logistics = [];
        foreach ((@$assignment->logistics ?? []) as $key => $item) {
            $logistics[] = [
                "id" => $item->id,
                "title" => "Logistik " . $key + 1,
                "description" => null,
                "details" => [
                    "title" => @$item->goods->name,
                    "description" => @$item->goods->type,
                    "value" => @$item->goods->value ?? null,
                    "type_unit" => "Unit"
                ]
            ];
        }

        $data = [
            "id" => $assignment->id,
            "status" => $assignment->status,
            "disaster" => @$assignment->disaster ? [
                "id" => @$assignment->disaster->id,
                "title" => @$assignment->disaster->title,
                "description" => @$assignment->disaster->description,
                "latitude" => @$assignment->station->latitude ?? @$assignment->disaster->latitude,
                "longitude" => @$assignment->station->longitude ?? @$assignment->disaster->longitude,
                "images" => $images
            ] : null,
            "origin" => @$assignment->branchOffice ? [
                "title" => @$assignment->branchOffice->name,
                "latitude" => @$assignment->branchOffice->latitude,
                "longitude" => @$assignment->branchOffice->longitude,
            ] : null,
            "destination" => @$assignment->branchOffice ? [
                "title" => @$assignment->disaster->district->name ?? @$assignment->station->name,
                "latitude" => @$assignment->station->latitude ?? @$assignment->disaster->latitude,
                "longitude" => @$assignment->station->longitude ?? @$assignment->disaster->longitude,
            ] : null,
            "logistics" => $logistics
        ];

        return response()->json([
            "message" => "Detail assignment data successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * Update Location
     */
    public function updateLocation(Request $request, string $id)
    {
        $request->validate([
            'latitude' => ['string', 'required'],
            'longitude' => ['string', 'required'],
        ]);

        $assignment = Delivery::find($id);

        if (!@$assignment) {
            return response()->json([
                "message" => "Assignment not found"
            ], 404);
        }

        $assignment->latitude = $request->latitude;
        $assignment->longitude = $request->longitude;
        $assignment->save();

        return response()->json([
            "message" => "Assignment location successfully updated",
        ], 200);
    }

    /**
     * Update Assignment Status
     */
    public function updateAssignmentStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => ['string', 'required', 'in:active,canceled,finished'],
            'recipient_name' => ['string', 'required'],
            'images.*' => ['string'],
        ]);

        $assignment = Delivery::find($id);

        if (!@$assignment) {
            return response()->json([
                "message" => "Assignment not found"
            ], 404);
        }

        $images = [];
        foreach ($request->images ?? [] as $item) {
            $images[] = str_replace(url('storage') . '/', '', $item);
        }

        $assignment->status = $request->status;
        $assignment->recipient_name = $request->recipient_name;
        $assignment->images = json_encode($images);
        $assignment->save();

        return response()->json([
            "message" => "Assignment status successfully updated",
        ], 200);
    }
}

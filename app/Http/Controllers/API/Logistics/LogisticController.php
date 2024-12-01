<?php

namespace App\Http\Controllers\API\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistic;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    /**
     * List Logistics
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $logistics = Logistic::paginate($request->limit ?? 10);

        $data = [];

        foreach ($logistics as $item) {
            $images = [];
            foreach (@json_decode(@$item->disaster->images) ?? [] as $row) {
                $images[] = url('storage').'/'.$row;
            }

            $data[] = [
                "id" => $item->id,
                "disaster" => $item->disaster == null ? null : [
                    "id" => $item->disaster->id,
                    "title" => $item->disaster->title,
                    "category" => @$item->disaster->category == null ? null : [
                        "id" => $item->disaster->category->id,
                        "name" => $item->disaster->category->name,
                    ],
                    "images" => $images,
                    "description" => $item->disaster->description,
                    "date" => $item->disaster->date,
                    "status" => $item->disaster->status,
                    "address" => $item->disaster->address,
                    "created_by" => @$item->disaster->user == null ? null : [
                        "id" => @$item->disaster->user->id,
                        "name" => @$item->disaster->user->name
                    ]
                ],
                "branch_office" => @$item->branch_office ? [
                    "id" => $item->branch_office->id,
                    "name" => $item->branch_office->name,
                ] : null,
                "date" => $item->date,
                "status" => $item->status,
                "created_at" => $item->created_at->format('Y-m-d H:i:s')
            ];
        }

        return response()->json([
            "message" => "Logistics data successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * Store Logistic
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_office_id' => ['required', 'integer'],
            'district_id' => ['required', 'integer'],
            'origin_address' => ['required', 'string'],
            'telp' => ['required', 'string'],
            'weight' => ['required', 'string'],
            'image' => ['required', 'string'],
            'date' => ['required', 'string'],
            'receipt_number' => ['required', 'string'],
            'sender_name' => ['required', 'string'],
            'goods.name' => ['required', 'string'],
            'goods.type' => ['required', 'string'],
        ]);


        return response()->json([
            "message" => "Your logistic successfully created, please wait we will confirm your logistics"
        ], 200);
    }

    /**
     * Detail Logistic
     */
    public function show(string $id)
    {
        $logistic = Logistic::find($id);

        if (!$logistic) {
            return response()->json([
                "message" => "Logistic data not found",
            ], 404);
        }

        $images = [];
        foreach (@json_decode($logistic->images) ?? [] as $row) {
            $images[] = url('storage').'/'.$row;
        }

        $data = [
            "id" => $logistic->id,
            "disaster" => $logistic->disaster == null ? null : [
                "id" => $logistic->disaster->id,
                "title" => $logistic->disaster->title,
                "category" => @$logistic->disaster->category == null ? null : [
                    "id" => $logistic->disaster->category->id,
                    "name" => $logistic->disaster->category->name,
                ],
                "images" => $images,
                "description" => $logistic->disaster->description,
                "date" => $logistic->disaster->date,
                "status" => $logistic->disaster->status,
                "address" => $logistic->disaster->address,
                "created_by" => @$logistic->disaster->user == null ? null : [
                    "id" => @$logistic->disaster->user->id,
                    "name" => @$logistic->disaster->user->name
                ]
            ],
            "branch_office" => @$logistic->branch_office ? [
                "id" => $logistic->branch_office->id,
                "name" => $logistic->branch_office->name,
            ] : null,
            "date" => $logistic->date,
            "status" => $logistic->status,
            "created_at" => $logistic->created_at->format('Y-m-d H:i:s')
        ];

        return response()->json([
            "message" => "Detail logistic successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * Logistic Receipt Track
     */
    public function logisticReceiptTracks(string $id)
    {

        $logistic = Logistic::find($id);

        if (!$logistic) {
            return response()->json([
                "message" => "Logistic data not found",
            ], 404);
        }

        $data = [
            [
                "title" => "Lorem ipsum dolor",
                "date" => "2022-02-02 00:00:00"
            ],
            [
                "title" => "Lorem ipsum dolor",
                "date" => "2022-02-02 00:00:00"
            ],
        ];

        return response()->json([
            "message" => "Detail logistic receipt tracks successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

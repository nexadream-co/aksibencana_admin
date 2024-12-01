<?php

namespace App\Http\Controllers\API\Disasters;

use App\Http\Controllers\Controller;
use App\Models\Disaster;
use App\Models\DisasterCategory;
use Illuminate\Http\Request;

class DisasterController extends Controller
{
    /**
     * List Disasters
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
            'category_id' => ['integer'],
        ]);

        $disasters = Disaster::query();

        if ($request->category_id) {
            $disasters = $disasters->where('disaster_category_id', $request->category_id);
        }

        $disasters = $disasters->paginate($request->limit ?? 10);

        $data = [];

        foreach ($disasters as $item) {

            $images = [];
            foreach (@json_decode($item->images) ?? [] as $row) {
                $images[] = url('storage').'/'.$row;
            }

            $data[] = [
                "id" => $item->id,
                "title" => $item->title,
                "category" => @$item->category == null ? null : [
                    "id" => $item->category->id,
                    "name" => $item->category->name,
                ],
                "images" => $images,
                "description" => $item->description,
                "date" => $item->date,
                "status" => $item->status,
                "address" => $item->address,
                "created_by" => @$item->user == null ? null : [
                    "id" => @$item->user->id,
                    "name" => @$item->user->name
                ]
            ];
        }

        return response()->json([
            "message" => "Disaster data successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * List My Disasters
     */
    public function me(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
            'category_id' => ['integer'],
        ]);

        $disasters = Disaster::query();

        if ($request->category_id) {
            $disasters = $disasters->where('disaster_category_id', $request->category_id);
        }

        $disasters = $disasters->where('created_by', $request->user()->id)->paginate($request->limit ?? 10);

        $data = [];

        foreach ($disasters as $item) {
            $images = [];
            foreach (@json_decode($item->images) ?? [] as $row) {
                $images[] = url('storage').'/'.$row;
            }

            $data[] = [
                "id" => $item->id,
                "title" => $item->title,
                "category" => @$item->category == null ? null : [
                    "id" => $item->category->id,
                    "name" => $item->category->name,
                ],
                "images" => $images,
                "description" => $item->description,
                "date" => $item->date,
                "status" => $item->status,
                "address" => $item->address,
                "created_by" => @$item->user == null ? null : [
                    "id" => @$item->user->id,
                    "name" => @$item->user->name
                ]
            ];
        }

        return response()->json([
            "message" => "My disaster data successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * List Disaster Categories
     */
    public function categories()
    {
        $disaster_categories = DisasterCategory::orderBy('name', 'asc')->get();

        $data = [];

        foreach ($disaster_categories as $item) {
            $data[] = [
                "id" => $item->id,
                "name" => $item->name,
            ];
        }

        return response()->json([
            "message" => "Disaster categories data successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * Create Disaster
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string'],
            'date' => ['required', 'string'],
            'images' => ['required', 'array'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'district_id' => ['required', 'integer'],
            'disaster_category_id' => ['required', 'integer'],
        ]);

        $images = [];
        foreach ($request->images ?? [] as $item) {
            $images[] = str_replace(url('storage') . '/', '', $item);
        }

        Disaster::create([
            'title' => $request->title,
            'created_by' => $request->user()->id,
            'description' => $request->description,
            'address' => $request->address,
            'date' => $request->date,
            'images' => json_encode($images),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'district_id' => $request->district_id,
            'status' => "request",
        ]);

        return response()->json([
            "message" => "Your registration disaster successfully, please wait we will confirm disaster"
        ], 200);
    }

    /**
     * Detail Disaster
     */
    public function show(string $id)
    {
        $disaster = Disaster::find($id);

        if (!$disaster) {
            return response()->json([
                "message" => "Disaster data not found",
            ], 404);
        }

        $images = [];
        foreach (@json_decode($disaster->images) ?? [] as $row) {
            $images[] = url('storage').'/'.$row;
        }

        $data = [
            "id" => $disaster->id,
            "title" => $disaster->title,
            "category" => @$disaster->category == null ? null : [
                "id" => $disaster->category->id,
                "name" => $disaster->category->name,
            ],
            "images" => $images,
            "description" => $disaster->description,
            "date" => $disaster->date,
            "status" => $disaster->status,
            "address" => $disaster->address,
            "latitude" => $disaster->latitude,
            "longitude" => $disaster->longitude,
            "created_by" => @$disaster->user == null ? null : [
                "id" => @$disaster->user->id,
                "name" => @$disaster->user->name
            ]
        ];

        return response()->json([
            "message" => "Detail disaster successfully retrieved",
            "data" => $data,
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\API\Donations;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCategory;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * List Donations
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
            'category_id' => ['integer'],
        ]);

        $results = [];
        $donations = Donation::query();

        if($request->category_id){
            $donations->where('donation_category_id', $request->category_id);
        }

        $donations = $donations->paginate($request->limit ?? 10);

        foreach ($donations as $item) {
            $images = [];

            foreach (@json_decode($item->images) ?? [] as $item) {
                $images = url('storage').'/'.$item;
            }

            $results[] = [
                "id" => $item->id,
                "title" => $item->title,
                "images" => $images,
                "category" => @$item->category ? [
                    "id" => $item->category->id,
                    "name" => $item->category->name,
                ] : null,
                "description" => $item->description,
                "status_donation" => $item->status,
                "total_donators" => 20,
                "start_date" => $item->start_date,
                "end_date" => $item->end_date,
                "total_donation" => (int) $item->total,
                "target_donation" => (int) $item->target,
                "fundraiser" => @$item->fundraiser ? [
                    "id" => $item->fundraiser->id,
                    "name" => $item->fundraiser->name,
                    "photo" => $item->fundraiser->photo,
                    "description" =>url('storage').'/'. @$item->fundraiser->description,
                ] : null,
                "created_at" => $item->created_at->format("Y-m-d h:i:s")
            ];
        }

        return response()->json([
            "message" => "Donations data successfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     * List Donation Categories
     */
    public function categories()
    {
        $results = [];
        $donation_categories = DonationCategory::orderBy('name', 'asc')->get();

        foreach ($donation_categories as $item) {
            $results[] = [
                "id" => $item->id,
                "name" => $item->title
            ];
        }

        return response()->json([
            "message" => "Donation categories data successfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     * Store Donation
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'amount' => ['required', 'integer'],
            'show_identity' => ['string'],
            'pray' => ['string'],
        ]);

        return response()->json([
            "message" => "Donation successfully created, please do payment",
            "data" => [
                "snap_token" => "8498435",
                "snap_url" => "https://snap.id"
            ]
        ], 200);
    }

    /**
     * Detail Donation
     */
    public function show(string $id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                "message" => "Donation data not found",
            ], 404);
        }

        $images = [];

        foreach (@json_decode($donation->images) ?? [] as $donation) {
            $images = url('storage').'/'.$donation;
        }

        return response()->json([
            "message" => "Volunteer data successfully retrieved",
            "data" => [
                "id" => $donation->id,
                "title" => $donation->title,
                "category" => @$donation->category ? [
                    "id" => $donation->category->id,
                    "name" => $donation->category->name,
                ] : null,
                "images" => $images,
                "description" => $donation->description,
                "status_donation" => $donation->status,
                "total_donators" => 20,
                "start_date" => $donation->start_date,
                "end_date" => $donation->end_date,
                "total_donation" => (int) $donation->total,
                "target_donation" => (int) $donation->target,
                "fundraiser" => @$donation->fundraiser ? [
                    "id" => $donation->fundraiser->id,
                    "name" => $donation->fundraiser->name,
                    "photo" => $donation->fundraiser->photo,
                    "description" =>url('storage').'/'. @$donation->fundraiser->description,
                ] : null,
                "created_at" => $donation->created_at->format("Y-m-d h:i:s")
            ]
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

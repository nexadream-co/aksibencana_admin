<?php

namespace App\Http\Controllers\API\Volunteers;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolunteerController extends Controller
{

    /**
     * Register Volunteer
     */
    public function register(Request $request)
    {
        $request->validate([
            'date_of_birth' => ['required', 'string'],
            'address' => ['required', 'string'],
            'health_status' => ['required', 'string'],
            'ktp' => ['required', 'string'],
            'district_id' => ['required', 'string'],
            'categories' => ['required', 'array'],
            'abilities' => ['required', 'array'],
            'whatsapp_number' => ['string'],
        ]);

        DB::beginTransaction();

        $volunteer = Volunteer::create([
            "user_id" => $request->user()->id,
            "date_of_birth" => $request->date_of_birth,
            "address" => $request->address,
            "health_status" => $request->health_status,
            "whatsapp_number" => $request->whatsapp_number,
            "district_id" => $request->district_id,
            "ktp" => $request->ktp,
            "categories" => json_encode($request->categories),
            "availability_status" => "active",
            "status" => "request",
        ]);

        // Insert to ability
        // ...

        DB::commit();

        return response()->json([
            "message" => "Your registration as volunteer succesfully send, please wait we will confirm your proposal"
        ], 200);
    }

    /**
     * Detail Volunteer
     */
    public function show(string $id)
    {

        $volunteer = Volunteer::find($id);

        if(!$volunteer){
            return response()->json([
                "message" => "Volunteer data not found",
            ], 404);
        }

        return response()->json([
            "message" => "Volunteer data successfully retrieved",
            "data" => [
                "id" => $volunteer->id,
                "status" => $volunteer->status,
                "address" => $volunteer->address
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

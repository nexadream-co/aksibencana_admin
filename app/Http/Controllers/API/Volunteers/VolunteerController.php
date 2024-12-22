<?php

namespace App\Http\Controllers\API\Volunteers;

use App\Http\Controllers\Controller;
use App\Models\Ability;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolunteerController extends Controller
{
    /**
     * List Abilities
     */
    public function abilities()
    {
        $data = Ability::all();
        $results = [];

        foreach ($data as $item) {
            $results[] = [
                "id" => $item->id,
                "name" => $item->name
            ];
        }

        return response()->json([
            "message" => "Ability data successfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     * Register Volunteer
     */
    public function register(Request $request)
    {
        $request->validate([
            'date_of_birth' => ['nullable'],
            'address' => ['required', 'string'],
            'health_status' => ['required', 'string'],
            'ktp' => ['required', 'string'],
            'district_id' => ['required', 'exists:districts,id'],
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
            "ktp" => str_replace(url('storage') . '/', '', $request->ktp),
            "categories" => json_encode($request->categories),
            "availability_status" => "active",
            "status" => "requested",
        ]);

        $volunteer->abilities()->sync($request->abilities);

        DB::commit();

        return response()->json([
            "message" => "Your registration as volunteer succesfully send, please wait we will confirm your proposal"
        ], 200);
    }

    /**
     * Detail Volunteer
     */
    public function show(Request $request)
    {
        $user = User::find($request->user()->id);
        $volunteer = Volunteer::where('user_id', @$user->id)->first();

        if (!$volunteer) {
            return response()->json([
                "message" => "Volunteer data not found",
            ], 404);
        }

        return response()->json([
            "message" => "Volunteer data successfully retrieved",
            "data" => [
                "id" => $volunteer->id,
                "date_of_birth" => $volunteer->date_of_birth,
                "address" => $volunteer->address,
                "district" => [
                    "id" => @$volunteer->id,
                    "name" => @$volunteer->district->name,
                    "city" => [
                        "id" => @$volunteer->district->city->id,
                        "name" => @$volunteer->district->city->name,
                        "province" => [
                            "id" => @$volunteer->district->city->province->id,
                            "name" => @$volunteer->district->city->province->name,
                        ]
                    ],
                ],
                "categories" => @json_decode(@$volunteer->categories) ?? [],
                "whatsapp_number" => $volunteer->whatsapp_number,
                "health_status" => $volunteer->health_status,
                "abilities" => $volunteer->abilities->pluck('id'),
                "status" => $volunteer->status,
                "availability_status" => $volunteer->availability_status == "active",
                "status" => $volunteer->status,
                "ktp" => url('storage') . '/' . $volunteer->ktp,
            ]
        ], 200);
    }

    /**
     * Update Volunteer
     */
    public function update(Request $request)
    {
        $request->validate([
            'date_of_birth' => ['nullable'],
            'address' => ['required', 'string'],
            'health_status' => ['required', 'string'],
            'ktp' => ['required', 'string'],
            'district_id' => ['required', 'exists:districts,id'],
            'categories' => ['required', 'array'],
            'abilities' => ['required', 'array'],
            'whatsapp_number' => ['string'],
        ]);

        $volunteer = Volunteer::where('user_id', $request->user()->id)->first();

        if ($volunteer->status == 'requested') {
            return response()->json([
                "message" => "Your volunteer status not activated yet",
            ], 404);
        }

        if (!$volunteer) {
            return response()->json([
                "message" => "Volunteer data not found",
            ], 404);
        }

        DB::beginTransaction();

        $volunteer->district_id = $request->district_id;
        $volunteer->date_of_birth = $request->date_of_birth;
        $volunteer->address = $request->address;
        $volunteer->health_status = $request->health_status;
        $volunteer->whatsapp_number = $request->whatsapp_number;
        $volunteer->categories = json_encode($request->categories);
        // $volunteer->availability_status = $request->availability_status ? 'active' : 'inactive';
        // $volunteer->status = $request->status ? 'active' : 'inactive';
        $volunteer->abilities()->sync($request->abilities);

        if ($request->ktp) {
            $volunteer->ktp = str_replace(url('storage') . '/', '', $request->ktp);
        }

        $volunteer->save();

        DB::commit();

        return response()->json([
            "message" => "Volunteer data succesfully updated"
        ], 200);
    }

    /**
     * Update Status Volunteer
     */
    public function updateStatusVolunteer(Request $request)
    {
        $request->validate([
            'status' => ['string', 'required', 'in:active,inactive'],
        ]);

        $volunteer = Volunteer::where('user_id', $request->user()->id)->first();

        if ($volunteer->status == 'requested') {
            return response()->json([
                "message" => "Your volunteer status not activated yet",
            ], 404);
        }

        if ($volunteer->status == 'rejected') {
            return response()->json([
                "message" => "Your volunteer status rejected, cannot update",
            ], 404);
        }

        if (!$volunteer) {
            return response()->json([
                "message" => "Volunteer data not found",
            ], 404);
        }

        $volunteer->status = $request->status;
        $volunteer->save();

        return response()->json([
            "message" => "Volunteer status succesfully updated to " . $request->status
        ], 200);
    }

    /**
     * Update Availability Status Volunteer
     */
    public function updateAvailabilityStatusVolunteer(Request $request)
    {
        $request->validate([
            'availability_status' => ['boolean', 'required'],
        ]);

        $volunteer = Volunteer::where('user_id', $request->user()->id)->first();

        if (!$volunteer) {
            return response()->json([
                "message" => "Volunteer data not found",
            ], 404);
        }

        $volunteer->availability_status = $request->availability_status ? 'active' : 'inactive';
        $volunteer->save();

        return response()->json([
            "message" => "Volunteer availability status succesfully updated to " . ($request->availability_status ? 'active' : 'inactive')
        ], 200);
    }

    /**
     * Assignment Volunteers
     */
    public function assignmentVolunteers(Request $request)
    {
        $volunteer = Volunteer::where('user_id', $request->user()->id)->first();
        if (!@$volunteer) {
            return response()->json([
                "message" => "You are not yet registered as volunteer",
            ], 400);
        }

        $data = VolunteerAssignment::where('volunteer_id', $volunteer->id)->where('status', 'active')->get();
        $results = [];

        foreach ($data as $item) {
            $results[] = [
                "id" => $item->id,
                "disaster_id" => $item->disaster_id,
                "title" => $item->title . (@$item->station->title ? ", {$item->station->title}" : " , {$item->disaster->title}"),
                "description" => $item->description,
                "date" => $item->start_date
            ];
        }

        return response()->json([
            "message" => "Assignment volunteer data successfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     *  History Assignment Volunteers
     */
    public function historyAssignmentVolunteers(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $volunteer = Volunteer::where('user_id', $request->user()->id)->first();
        if (!@$volunteer) {
            return response()->json([
                "message" => "You are not yet registered as volunteer",
            ], 400);
        }

        $data = VolunteerAssignment::where('volunteer_id', $volunteer->id)->whereIn('status', ['finished', 'canceled'])->paginate($request->limit ?? 10);
        $results = [];

        foreach ($data as $item) {
            $results[] = [
                "id" => $item->id,
                "disaster_id" => $item->disaster_id,
                "title" => $item->title . (@$item->station->title ? ", {$item->station->title}" : " , {$item->disaster->title}"),
                "description" => $item->description,
                "date" => $item->start_date
            ];
        }

        return response()->json([
            "message" => "History assignment volunteer data successfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     * Update Status Assignment
     */
    public function updateStatusAssignment(Request $request, $id)
    {
        $request->validate([
            'status' => ['string', 'required', 'in:finished,canceled'],
        ]);

        $assignment = VolunteerAssignment::find($id);

        if (!$assignment) {
            return response()->json([
                "message" => "Volunteer assignment data not found",
            ], 404);
        }

        $assignment->status = $request->status;
        $assignment->save();

        return response()->json([
            "message" => "Volunteer assignment status succesfully updated to " . $request->status
        ], 200);
    }
}

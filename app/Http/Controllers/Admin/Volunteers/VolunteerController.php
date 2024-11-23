<?php

namespace App\Http\Controllers\Admin\Volunteers;

use App\Http\Controllers\Controller;
use App\Models\Ability;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $volunteers = Volunteer::latest()->get();
        return view('pages.volunteers.index', compact('volunteers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $abilities = Ability::orderBy('name', 'asc')->get();
        return view('pages.volunteers.create', compact('abilities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_of_birth' => ['required', 'string'],
            'address' => ['required', 'string'],
            'health_status' => ['required', 'string'],
            'ktp' => ['required', 'string'],
            'user_id' => ['required', 'file'],
            'district_id' => ['required', 'string'],
            'district_id' => ['required', 'string'],
            'categories' => ['required', 'array'],
            'abilities' => ['required', 'array'],
            'whatsapp_number' => ['string'],
        ]);

        DB::beginTransaction();

        $volunteer = Volunteer::create([
            "district_id" => $request->district_id,
            "user_id" => $request->user_id,
            "date_of_birth" => $request->date_of_birth,
            "address" => $request->address,
            "health_status" => $request->health_status,
            "whatsapp_number" => $request->whatsapp_number,
            "district_id" => $request->district_id,
            "ktp" => $request->ktp,
            "categories" => json_encode($request->categories),
            "availability_status" => $request->availability_status ? 'active' : 'inactive',
            "status" => $request->status ? 'active' : 'inactive',
        ]);

        // $volunteer->abilities()->attach($request->abilities);
        $volunteer->abilities()->sync($request->abilities);

        DB::commit();

        session()->flash('success', 'Volunteer successfully created');

        return redirect()->route('volunteers');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

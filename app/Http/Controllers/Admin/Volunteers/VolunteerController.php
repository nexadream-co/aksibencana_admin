<?php

namespace App\Http\Controllers\Admin\Volunteers;

use App\Http\Controllers\Controller;
use App\Models\Ability;
use App\Models\User;
use App\Models\Volunteer;
use App\Notifications\VolunteerStatusUpdated;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolunteerController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
            'ktp' => ['required', 'file'],
            'user_id' => ['required', 'string'],
            'district_id' => ['required', 'string'],
            'categories' => ['required', 'array'],
            'abilities' => ['required', 'array'],
            'whatsapp_number' => ['string'],
        ]);

        $is_registered = Volunteer::where('user_id', $request->user_id)->where('status', '<>', 'rejected')->first();
        if ($is_registered) {
            session()->flash('error', 'User already regitered as volunteer');
            return back()->withInput();
        }

        DB::beginTransaction();

        $volunteer = Volunteer::create([
            "district_id" => $request->district_id,
            "user_id" => $request->user_id,
            "date_of_birth" => $request->date_of_birth,
            "address" => $request->address,
            "health_status" => $request->health_status,
            "whatsapp_number" => $request->whatsapp_number,
            "ktp" => $request->ktp,
            "categories" => json_encode($request->categories),
            "availability_status" => $request->availability_status ? 'active' : 'inactive',
            "status" => $request->status,
        ]);

        // $volunteer->abilities()->attach($request->abilities);
        $volunteer->abilities()->sync($request->abilities);

        $image = null;
        if ($request->hasFile('ktp')) {
            $image = $this->upload($request, 'images', 'ktp');
            $volunteer->ktp = $image;
            $volunteer->save();
        }

        DB::commit();

        session()->flash('success', 'Volunteer successfully created');

        return redirect()->route('volunteers');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $volunteer = Volunteer::find($id);

        if (!$volunteer) return abort(404);

        $abilities = Ability::orderBy('name', 'asc')->get();

        return view('pages.volunteers.edit', compact('volunteer', 'abilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $volunteer = Volunteer::find($id);

        if (!$volunteer) return abort(404);

        $request->validate([
            'date_of_birth' => ['required', 'string'],
            'address' => ['required', 'string'],
            'health_status' => ['required', 'string'],
            'user_id' => ['required', 'string'],
            'district_id' => ['required', 'string'],
            'categories' => ['required', 'array'],
            'abilities' => ['required', 'array'],
            'whatsapp_number' => ['string'],
        ]);

        DB::beginTransaction();

        // if (@$volunteer->status == 'requested' && ($request->status == 'active' || $request->status == 'rejected')) {
        //     $volunteer->status = $request->status;

        //     $user = User::find(@$volunteer->user_id);
        //     @$user->notify(new VolunteerStatusUpdated($volunteer));
        // }

        try {
            if (@$volunteer->status == 'requested' && ($request->status == 'active' || $request->status == 'rejected')) {
                $volunteer->status = $request->status;

                $user = User::find(@$volunteer->user_id);
                @$user->notify(new VolunteerStatusUpdated($volunteer));
            }
        } catch (\Throwable $th) {
        }

        $volunteer->district_id = $request->district_id;
        $volunteer->user_id = $request->user_id;
        $volunteer->date_of_birth = $request->date_of_birth;
        $volunteer->address = $request->address;
        $volunteer->health_status = $request->health_status;
        $volunteer->whatsapp_number = $request->whatsapp_number;
        $volunteer->categories = json_encode($request->categories);
        $volunteer->availability_status = $request->availability_status ? 'active' : 'inactive';
        $volunteer->status = $request->status;
        $volunteer->abilities()->sync($request->abilities);

        $image = null;
        if ($request->hasFile('ktp')) {
            $image = $this->upload($request, 'images', 'ktp');
            $volunteer->ktp = $image;
        }

        $volunteer->save();

        DB::commit();
        session()->flash('success', 'Volunteer successfully updated');

        return redirect()->route('volunteers');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $volunteer = Volunteer::find($id);

        if (!$volunteer) return abort(404);

        DB::beginTransaction();

        $volunteer->abilities()->detach();

        $volunteer->delete();

        DB::commit();

        session()->flash('success', 'Volunteer successfully deleted');

        return redirect()->route('volunteers');
    }
}

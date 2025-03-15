<?php

namespace App\Http\Controllers\Admin\Volunteers;

use App\Http\Controllers\Controller;
use App\Models\Disaster;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerAssignment;
use App\Notifications\AssignmentStatusFinished;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class VolunteerAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $volunteer = Volunteer::find($id);

        if(!@$volunteer) return abort(404);

        $assignments = VolunteerAssignment::where('volunteer_id', $id)->latest()->get();
        return view('pages.volunteers.assignments.index', compact('assignments', 'volunteer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $volunteer = Volunteer::find($id);

        if(!@$volunteer) return abort(404);
        return view('pages.volunteers.assignments.create', compact('volunteer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'user_id' => ['required', 'string'],
            'disaster_id' => ['required', 'string'],
            'disaster_station_id' => ['required', 'string'],
            'title' => ['string', 'required'],
            'description' => ['string'],
            'status' => ['string', 'required'],
            'start_date' => ['string', 'required'],
            'end_date' => ['string', 'required'],
        ]);

        $assignment = new VolunteerAssignment();
        $assignment->volunteer_id = $id;
        $assignment->user_id = $request->user_id;
        $assignment->disaster_id = $request->disaster_id;
        $assignment->disaster_station_id = $request->disaster_station_id;
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->status = $request->status;
        $assignment->start_date = $request->start_date;
        $assignment->end_date = $request->end_date;
        $assignment->save();

        session()->flash('success', 'Volunteer assignment successfully created');

        return redirect()->route('volunteer_assignments', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, string $assignment_id)
    {
        $volunteer = Volunteer::find($id);

        if(!@$volunteer) return abort(404);

        $assignment = VolunteerAssignment::find($assignment_id);

        if(@!$assignment) return abort(404);

        return view('pages.volunteers.assignments.edit', compact('volunteer', 'assignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, string $assignment_id)
    {
        $request->validate([
            'user_id' => ['required', 'string'],
            'disaster_id' => ['required', 'string'],
            'disaster_station_id' => ['required', 'string'],
            'title' => ['string', 'required'],
            'description' => ['string'],
            'status' => ['string', 'required'],
            'start_date' => ['string', 'required'],
            'end_date' => ['string', 'required'],
        ]);

        $assignment = VolunteerAssignment::find($assignment_id);

        if(@!$assignment) return abort(404);

        $assignment->volunteer_id = $id;
        $assignment->user_id = $request->user_id;
        $assignment->disaster_id = $request->disaster_id;
        $assignment->disaster_station_id = $request->disaster_station_id;
        $assignment->title = $request->title;
        $assignment->description = $request->description;
        $assignment->status = $request->status;
        $assignment->start_date = $request->start_date;
        $assignment->end_date = $request->end_date;
        $assignment->save();

        if($request->status == 'finished'){
            try {
                $user = User::find($request->user_id);
                $pdf = Pdf::loadView('pdf.certificate', ['user' => $user, 'disaster' => @$assignment->disaster]);
                $pdfPath = storage_path('app/public/certificate-' . $user->id . '.pdf');
                $pdf->save($pdfPath);
                $user->notify(new AssignmentStatusFinished($pdfPath));
            } catch (\Throwable $th) {
            }
        }

        session()->flash('success', 'Volunteer assignment successfully updated');

        return redirect()->route('volunteer_assignments', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $assignment_id)
    {
        $volunteer = Volunteer::find($id);

        if(!@$volunteer) return abort(404);

        $assignment = VolunteerAssignment::find($assignment_id);

        if(@!$assignment) return abort(404);

        $assignment->delete();

        session()->flash('success', 'Volunteer assignment successfully deleted');

        return redirect()->route('volunteer_assignments', [$id]);
    }

    public function generateCertificate(string $id, string $assignment_id)
    {
        $assignment = VolunteerAssignment::find($assignment_id);
        $user = User::find($assignment->user_id);
        $pdf = Pdf::loadView('pdf.certificate', ['user' => $user, 'disaster' => @$assignment->disaster])->setPaper('a4', 'landscape');
        $pdfPath = storage_path('app/public/certificate_' . $user->id . '.pdf');
        $pdf->save($pdfPath);
        
        // return $pdf->stream('sertifikat-penghargaan.pdf');

        $user->notify(new AssignmentStatusFinished($pdfPath));

        session()->flash('success', 'Certificate successfully sent by email');

        return redirect()->route('volunteer_assignments', [$id]);
    }
}

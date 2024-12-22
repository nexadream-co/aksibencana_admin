<?php

namespace App\Http\Controllers\Admin\BranchOffices;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use Illuminate\Http\Request;

class BranchOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BranchOffice::latest()->get();
        return view('pages.branch_offices.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.branch_offices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'district_id' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $branch_office = new BranchOffice();
        $branch_office->district_id = $request->district_id;
        $branch_office->name = $request->name;
        $branch_office->latitude = $request->latitude;
        $branch_office->longitude = $request->longitude;
        $branch_office->address = $request->address;
        $branch_office->status = $request->status ? 'active' : 'inactive';
        $branch_office->save();


        session()->flash('success', 'Branch office successfully created');

        return redirect()->route('branch_offices');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $branch_office = BranchOffice::find($id);

        if (!$branch_office) return abort(404);

        return view('pages.branch_offices.edit', compact('branch_office'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'district_id' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);


        $branch_office = BranchOffice::find($id);

        if (!$branch_office) return abort(404);

        $branch_office->district_id = $request->district_id;
        $branch_office->name = $request->name;
        $branch_office->address = $request->address;
        $branch_office->latitude = $request->latitude;
        $branch_office->longitude = $request->longitude;
        $branch_office->status = $request->status ? 'active' : 'inactive';
        $branch_office->save();


        session()->flash('success', 'Branch office successfully updated');

        return redirect()->route('branch_offices');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $branch_office = BranchOffice::find($id);

        if (!$branch_office) return abort(404);

        $branch_office->delete();

        session()->flash('success', 'Branch office successfully deleted');

        return redirect()->route('branch_offices');
    }
}

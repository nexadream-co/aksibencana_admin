<?php

namespace App\Http\Controllers\Admin\Disasters;

use App\Http\Controllers\Controller;
use App\Models\Disaster;
use App\Models\DisasterStation;
use Illuminate\Http\Request;

class DisasterStationContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $id)
    {
        $disaster = Disaster::find($id);

        if(!@$disaster) return abort(404);

        $stations = DisasterStation::where('disaster_id', $id)->latest()->get();
        return view('pages.disasters.stations.index', compact('stations', 'disaster'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $disaster = Disaster::find($id);

        if(!@$disaster) return abort(404);

        return view('pages.disasters.stations.create', compact('disaster'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'category' => ['required', 'string'],
        ]);

        $disaster = Disaster::find($id);

        if(!@$disaster) return abort(404);

        $station = new DisasterStation();
        $station->disaster_id = $id;
        $station->name = $request->name;
        $station->latitude = $request->latitude;
        $station->longitude = $request->longitude;
        $station->category = $request->category;
        $station->save();

        session()->flash('success', 'Disaster station successfully created');

        return redirect()->route('disaster_stations', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, string $station_id)
    {
        $disaster = Disaster::find($id);

        if(!@$disaster) return abort(404);

        $station = DisasterStation::find($station_id);

        if(!@$station) return abort(404);

        return view('pages.disasters.stations.edit', compact('disaster', 'station'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, string $station_id)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'category' => ['required', 'string'],
        ]);

        $disaster = Disaster::find($id);

        if(!@$disaster) return abort(404);

        $station = DisasterStation::find($station_id);

        if(!@$station) return abort(404);
        $station->name = $request->name;
        $station->latitude = $request->latitude;
        $station->longitude = $request->longitude;
        $station->category = $request->category;
        $station->save();

        session()->flash('success', 'Disaster station successfully created');

        return redirect()->route('disaster_stations', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $station_id)
    {
        $station = DisasterStation::find($station_id);

        if(!@$station) return abort(404);

        $station->delete();

        session()->flash('success', 'Disaster station successfully deleted');

        return redirect()->route('disaster_stations', [$id]);
    }
}

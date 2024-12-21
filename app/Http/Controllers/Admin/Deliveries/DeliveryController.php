<?php

namespace App\Http\Controllers\Admin\Deliveries;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use App\Models\Delivery;
use App\Notifications\CourierAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $deliveries = Delivery::latest();
        if ($request->user()->getRoleNames()[0] == 'admin') {
            $deliveries = $deliveries->where('branch_office_id', $request->user()->branch_office_id);
        }
        $deliveries = $deliveries->get();
        return view('pages.deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branch_offices = BranchOffice::orderBy('name', 'asc')->get();
        return view('pages.deliveries.create', compact('branch_offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'disaster_id' => ['required', 'string'],
            'disaster_station_id' => ['string'],
            'branch_office_id' => ['required', 'string'],
            'user_id' => ['required', 'string'],
            'date' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        $delivery = new Delivery();
        $delivery->disaster_id = $request->disaster_id;
        $delivery->disaster_station_id = $request->disaster_station_id;
        $delivery->branch_office_id = $request->branch_office_id;
        $delivery->delivery_by = $request->user_id;
        $delivery->date = $request->date;
        $delivery->status = $request->status;
        $delivery->delivered_at = date('Y-m-d h:i:s');
        $delivery->save();

        try {
            @$delivery->courier->notify(new CourierAssignment($delivery));
        } catch (\Throwable $th) {
        }

        DB::commit();

        session()->flash('success', 'Delivery successfully created');

        return redirect()->route('deliveries');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $delivery = Delivery::find($id);

        if (!@$delivery) return abort(404);

        $branch_offices = BranchOffice::orderBy('name', 'asc')->get();
        return view('pages.deliveries.edit', compact('branch_offices', 'delivery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'disaster_id' => ['required', 'string'],
            'disaster_station_id' => ['string'],
            'branch_office_id' => ['required', 'string'],
            'user_id' => ['required', 'string'],
            'date' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $delivery = Delivery::find($id);

        if (!@$delivery) return abort(404);

        $delivery->disaster_id = $request->disaster_id;
        $delivery->disaster_station_id = $request->disaster_station_id;
        $delivery->branch_office_id = $request->branch_office_id;
        $delivery->delivery_by = $request->user_id;
        $delivery->date = $request->date;
        $delivery->status = $request->status;
        $delivery->save();

        session()->flash('success', 'Delivery successfully updated');

        return redirect()->route('deliveries');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delivery = Delivery::find($id);

        if (!@$delivery) return abort(404);

        $delivery->delete();

        session()->flash('success', 'Delivery successfully deleted');

        return redirect()->route('deliveries');
    }
}

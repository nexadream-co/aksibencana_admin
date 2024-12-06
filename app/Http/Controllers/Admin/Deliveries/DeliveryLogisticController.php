<?php

namespace App\Http\Controllers\Admin\Deliveries;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Logistic;
use Illuminate\Http\Request;

class DeliveryLogisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $delivery = Delivery::find($id);
        if (!@$delivery) return abort(404);

        $logistics = Logistic::where('delivery_id', $id)->latest()->get();
        return view('pages.deliveries.logistics.index', compact('logistics', 'delivery'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $delivery = Delivery::find($id);
        if (!@$delivery) return abort(404);
        return view('pages.deliveries.logistics.create', compact('delivery'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'logistic_id' => ['required', 'string'],
        ]);

        $logistic = Logistic::find($request->logistic_id);
        if (!@$logistic) return abort(404);

        $logistic->delivery_id = $id;
        $logistic->save();

        session()->flash('success', 'Logistic successfully added');

        return redirect()->route('delivery_logistics', [$id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, string $logistic_id)
    {
        $delivery = Delivery::find($id);
        if (!@$delivery) return abort(404);

        $logistic = Logistic::find($logistic_id);
        if (!@$logistic) return abort(404);
        return view('pages.deliveries.logistics.create', compact('delivery', 'logistic_id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'logistic_id' => ['required', 'string'],
        ]);

        $logistic = Logistic::find($request->logistic_id);
        if (!@$logistic) return abort(404);

        $logistic->delivery_id = $id;
        $logistic->save();

        session()->flash('success', 'Logistic successfully updated');

        return redirect()->route('delivery_logistics', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, string $logistic_id)
    {
        $logistic = Logistic::find($logistic_id);
        if (!@$logistic) return abort(404);

        $logistic->delivery_id = null;
        $logistic->save();

        session()->flash('success', 'Logistic successfully removed');

        return redirect()->route('delivery_logistics', [$id]);
    }
}

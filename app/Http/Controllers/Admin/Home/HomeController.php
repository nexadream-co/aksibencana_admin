<?php

namespace App\Http\Controllers\Admin\Home;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Disaster;
use App\Models\Logistic;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $total_volunteer = Volunteer::where('status', 'active')->count();
        $total_logistic = Logistic::where('status', 'active')->count();
        $total_delivery = Delivery::where('status', 'active')->count();
        $total_disaster = Disaster::where('status', 'active')->count();

        $total = [
            'total_volunteer' => $total_volunteer,
            'total_logistic' => $total_logistic,
            'total_delivery' => $total_delivery,
            'total_disaster' => $total_disaster
        ];

        return view('pages.home.index', compact('total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

<?php

namespace App\Http\Controllers\Admin\Volunteers;

use App\Http\Controllers\Controller;
use App\Models\Ability;
use Illuminate\Http\Request;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $abilities = Ability::latest()->get();
        return view('pages.abilities.index', compact('abilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.abilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
        ]);

        Ability::create([
            'name' => $request->name
        ]);

        session()->flash('success', 'Ability successfully created');

        return redirect()->route('abilities');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ability = Ability::find($id);
        if (!$ability) return abort(404);
        return view('pages.abilities.edit', compact('ability'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string'],
        ]);

        $ability = Ability::find($id);

        if (!$ability) return abort(404);

        $ability->name = $request->name;
        $ability->save();

        session()->flash('success', 'Ability successfully updated');

        return redirect()->route('abilities');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ability = Ability::find($id);

        if (!$ability) return abort(404);
        $ability->delete();

        session()->flash('success', 'Ability successfully deleted');

        return redirect()->route('abilities');
    }
}

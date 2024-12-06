<?php

namespace App\Http\Controllers\Admin\Education;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Education::latest()->get();
        return view('pages.education.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.education.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        $image = null;
        if ($request->hasFile('banner')) {
            $image = $this->upload($request, 'images', 'banner');
        }

        $education = new Education();
        $education->title = $request->title;
        $education->contents = $request->description;
        $education->banner = $image;
        $education->save();

        session()->flash('success', 'Education successfully created');

        return redirect()->route('education');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $education = Education::find($id);

        if (!$education) return abort(404);
        return view('pages.education.edit', compact('education'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        $education = Education::find($id);

        if (!$education) return abort(404);

        $image = null;
        if ($request->hasFile('banner')) {
            $image = $this->upload($request, 'images', 'banner');
            $education->banner =  $image;
        }

        $education->title = $request->title;
        $education->contents = $request->description;
        $education->save();

        session()->flash('success', 'Education successfully updated');

        return redirect()->route('education');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $education = Education::find($id);

        if (!$education) return abort(404);

        $education->delete();

        session()->flash('success', 'Education successfully deleted');

        return redirect()->route('education');
    }
}

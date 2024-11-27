<?php

namespace App\Http\Controllers\Admin\Donations;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\Fundraiser;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donations = Donation::latest()->get();
        return view('pages.donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DonationCategory::orderBy('name', 'asc')->get();
        return view('pages.donations.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image' => ['required', 'file'],
            'start_date' => ['required', 'string'],
            'end_date' => ['required', 'string'],
            'target' => ['required', 'integer'],
            'fundraiser_name' => ['required', 'string'],
            'fundraiser_photo' => ['file'],
            'fundraiser_description' => ['string'],
        ]);

        DB::beginTransaction();

        $photo = null;
        if ($request->hasFile('fundraiser_photo')) {
            $photo = $this->upload($request, 'images', 'fundraiser_photo');
        }

        $fundraiser = Fundraiser::create([
            'name' => $request->fundraiser_name,
            'photo' => $photo,
            'description' => $request->fundraiser_description
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $this->upload($request, 'images', 'image');
        }

        $donation = new Donation();
        $donation->donation_category_id = $request->category_id;
        $donation->title = $request->title;
        $donation->description = $request->description;
        $donation->fundraiser_id = $fundraiser->id;
        $donation->images = $image != null ? json_encode([$image]) : null;
        $donation->start_date = $request->start_date;
        $donation->end_date = $request->end_date;
        $donation->total = $request->total ?? 0;
        $donation->target = $request->target ?? 0;
        $donation->status = $request->status ? 'active' : 'inactive';
        $donation->save();

        DB::commit();

        session()->flash('success', 'Donation successfully created');

        return redirect()->route('donations');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $donation = Donation::find($id);

        if (!$donation) return abort(404);

        $categories = DonationCategory::orderBy('name', 'asc')->get();
        return view('pages.donations.edit', compact('categories', 'donation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'string'],
            'end_date' => ['required', 'string'],
            'target' => ['required', 'integer'],
            'fundraiser_name' => ['required', 'string'],
            'fundraiser_description' => ['string'],
        ]);

        $donation = Donation::find($id);

        if (!$donation) return abort(404);

        $fundraiser = Fundraiser::find($donation->fundraiser_id);

        DB::beginTransaction();

        $photo = null;
        if ($request->hasFile('fundraiser_photo')) {
            $photo = $this->upload($request, 'images', 'fundraiser_photo');
        }else{
            $photo = $fundraiser->photo;
        }

        if($fundraiser) {
            $fundraiser->name = $request->fundraiser_name;
            $fundraiser->photo = $photo;
            $fundraiser->description = $request->fundraiser_description;
            $fundraiser->save();
        }

        $image = null;
        if ($request->hasFile('image')) {
            $image = $this->upload($request, 'images', 'image');
            $donation->images = $image != null ? json_encode([$image]) : null;
        }

        $donation->donation_category_id = $request->category_id;
        $donation->title = $request->title;
        $donation->description = $request->description;
        $donation->start_date = $request->start_date;
        $donation->end_date = $request->end_date;
        $donation->total = $request->total ?? 0;
        $donation->target = $request->target ?? 0;
        $donation->status = $request->status ? 'active' : 'inactive';
        $donation->save();

        DB::commit();

        session()->flash('success', 'Donation successfully updated');

        return redirect()->route('donations');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $donation = Donation::find($id);

        if (!$donation) return abort(404);

        DB::beginTransaction();

        Fundraiser::destroy($donation->fundraiser_id);
        $donation->delete();

        DB::commit();
        
        session()->flash('success', 'Donation successfully deleted');

        return redirect()->route('donations');
    }
}

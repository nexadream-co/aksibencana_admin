<?php

namespace App\Http\Controllers\Admin\Disasters;

use App\Http\Controllers\Controller;
use App\Models\Disaster;
use App\Models\DisasterCategory;
use App\Models\DisasterStation;
use App\Models\VolunteerAssignment;
use App\Notifications\DisasterStatusUpdated;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisasterController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disasters = Disaster::latest()->get();
        return view('pages.disasters.index', compact('disasters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DisasterCategory::orderBy('name', 'asc')->get();
        return view('pages.disasters.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'date' => ['required', 'string'],
            'address' => ['required', 'string'],
            'description' => ['required', 'string'],
            'images' => ['required', 'file'],
            'category_id' => ['string'],
            'district_id' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $image = [];
        if ($request->hasFile('images')) {
            $image[] = $this->upload($request, 'images', 'images');
        }

        $disaster = new Disaster();
        $disaster->title = $request->title;
        $disaster->description = $request->description;
        $disaster->address = $request->address;
        $disaster->date = $request->date;
        $disaster->district_id = $request->district_id;
        $disaster->disaster_category_id = $request->category_id;
        $disaster->status = $request->status;
        $disaster->images = json_encode($image);
        $disaster->created_by = $request->user()->id;
        $disaster->save();

        session()->flash('success', 'Disaster successfully created');

        return redirect()->route('disasters');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(string $id)
    {
        $disaster = Disaster::find($id);

        if (!$disaster) return abort(404);

        $assignments = VolunteerAssignment::where('disaster_id', $id)->latest()->get();

        return view('pages.disasters.detail', compact('disaster', 'assignments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $disaster = Disaster::find($id);

        if (!$disaster) return abort(404);

        $categories = DisasterCategory::orderBy('name', 'asc')->get();
        return view('pages.disasters.edit', compact('categories', 'disaster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'date' => ['required', 'string'],
            'address' => ['required', 'string'],
            'description' => ['required', 'string'],
            'category_id' => ['string'],
            'district_id' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $disaster = Disaster::find($id);

        if (!$disaster) return abort(404);

        $image = [];
        if ($request->hasFile('images')) {
            $image[] = $this->upload($request, 'images', 'images');
            $disaster->images =  json_encode($image);
        }

        try {
            if (@$disaster->status == 'requested' && ($request->status == 'active' || $request->status == 'rejected')) {
                $disaster->status = $request->status;
                @$disaster->user->notify(new DisasterStatusUpdated($disaster));
            }
        } catch (\Throwable $th) {
        }

        $disaster->title = $request->title;
        $disaster->description = $request->description;
        $disaster->address = $request->address;
        $disaster->date = $request->date;
        $disaster->district_id = $request->district_id;
        $disaster->disaster_category_id = $request->category_id;
        $disaster->status = $request->status;
        $disaster->save();

        session()->flash('success', 'Disaster successfully updated');

        return redirect()->route('disasters');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $disaster = Disaster::find($id);

        if (!$disaster) return abort(404);

        DB::beginTransaction();

        $disaster->delete();

        DB::commit();

        session()->flash('success', 'Disaster successfully deleted');

        return redirect()->route('disasters');
    }

    public function searchDisasters(Request $request): array
    {
        $data = [];
        $search = $request->q;
        $limit = $request->limit;

        $disasters = Disaster::where('status', 'active');
        if ($search) {
            foreach (explode(",", $search) as $key => $item) {
                if ($key == 0) {
                    $disasters->where('title', 'LIKE', '%' . $item . '%');
                } else {
                    $disasters->orWhere('title', 'LIKE', '%' . $item . '%');
                }
            }
        }

        $disasters = $disasters->paginate($limit ?? 10);
        foreach ($disasters as $item) {
            $data[] = [
                "id" => $item->id,
                "value" => $item->title
            ];
        }

        return $data;
    }

    public function searchDisasterStation(Request $request): array
    {
        $data = [];
        $search = $request->q;
        $limit = $request->limit;
        $disaster_id = $request->disaster_id;

        $disasters = DisasterStation::query();
        if ($search) {
            foreach (explode(",", $search) as $key => $item) {
                if ($key == 0) {
                    $disasters->where('name', 'LIKE', '%' . $item . '%');
                } else {
                    $disasters->orWhere('name', 'LIKE', '%' . $item . '%');
                }
            }
        }

        if ($disaster_id) {
            $disasters = $disasters->where('disaster_id', $disaster_id);
        }

        $disasters = $disasters->paginate($limit ?? 10);
        foreach ($disasters as $item) {
            $data[] = [
                "id" => $item->id,
                "value" => $item->name
            ];
        }

        return $data;
    }
}

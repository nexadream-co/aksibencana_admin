<?php

namespace App\Http\Controllers\Admin\Logistics;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use App\Models\Expedition;
use App\Models\Good;
use App\Models\Logistic;
use App\Notifications\LogisticStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $logistics = Logistic::whereHas('user')->whereHas('disaster')->latest();
        if ($request->user()->getRoleNames()[0] == 'admin') {
            $logistics = $logistics->where('branch_office_id', $request->user()->branch_office_id);
        }
        $logistics = $logistics->get();
        return view('pages.logistics.index', compact('logistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branch_offices = BranchOffice::orderBy('name', 'asc')->get();
        return view('pages.logistics.create', compact('branch_offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'disaster_id' => ['required', 'string'],
            'user_id' => ['string'],
            'district_id' => ['required', 'string'],
            'goods_name' => ['required', 'string'],
            'goods_type' => ['required', 'string'],
            'date' => ['required', 'string'],
            'branch_office_id' => ['required', 'string'],
            'expedition_name' => ['required', 'string'],
            'telp' => ['required', 'string'],
            'weight' => ['string'],
            'address' => ['string'],
            'receipt_code' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        $logistic = new Logistic();
        $logistic->disaster_id = $request->disaster_id;
        $logistic->user_id = $request->user_id ?? $request->user()->id;
        $logistic->date = $request->date;
        $logistic->branch_office_id = $request->branch_office_id;
        $logistic->status = $request->status;

        $goods = new Good();
        $goods->name = $request->goods_name;
        $goods->type = $request->goods_type;
        $goods->save();

        $logistic->good_id = $goods->id;

        $expedition = new Expedition();
        $expedition->district_id = $request->district_id;
        $expedition->name = $request->expedition_name;
        $expedition->origin_address = $request->address;
        $expedition->telp = $request->telp;
        $expedition->weight = $request->weight;
        $expedition->delivered_at = $request->date;
        $expedition->receipt_code = $request->receipt_code;
        $expedition->save();

        $logistic->expedition_id = $expedition->id;
        $logistic->save();

        DB::commit();

        session()->flash('success', 'Logistic successfully created');

        return redirect()->route('logistics');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $logistic = Logistic::find($id);

        if (!$logistic) return abort(404);

        $branch_offices = BranchOffice::orderBy('name', 'asc')->get();

        return view('pages.logistics.edit', compact('branch_offices', 'logistic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'disaster_id' => ['required', 'string'],
            'user_id' => ['string'],
            'district_id' => ['required', 'string'],
            'goods_name' => ['required', 'string'],
            'goods_type' => ['required', 'string'],
            'date' => ['required', 'string'],
            'branch_office_id' => ['required', 'string'],
            'expedition_name' => ['required', 'string'],
            'telp' => ['required', 'string'],
            'weight' => ['string'],
            'address' => ['string'],
            'receipt_code' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $logistic = Logistic::find($id);

        if (!$logistic) return abort(404);

        DB::beginTransaction();

        try {
            if (@$logistic->status == 'requested' && ($request->status == 'active' || $request->status == 'rejected')) {
                $logistic->status = $request->status;
                @$logistic->user->notify(new LogisticStatusUpdated($logistic));
            }
        } catch (\Throwable $th) {
        }

        $logistic->disaster_id = $request->disaster_id;
        $logistic->user_id = $request->user_id ?? $request->user()->id;
        $logistic->date = $request->date;
        $logistic->branch_office_id = $request->branch_office_id;
        $logistic->status = $request->status;

        $goods = Good::find($logistic->good_id) ?? new Good();
        $goods->name = $request->goods_name;
        $goods->type = $request->goods_type;
        $goods->save();

        $logistic->good_id = $goods->id;

        $expedition = Expedition::find($logistic->expedition_id) ?? new Expedition();
        $expedition->district_id = $request->district_id;
        $expedition->name = $request->expedition_name;
        $expedition->origin_address = $request->address;
        $expedition->telp = $request->telp;
        $expedition->weight = $request->weight;
        $expedition->delivered_at = $request->date;
        $expedition->receipt_code = $request->receipt_code;
        $expedition->save();

        $logistic->expedition_id = $expedition->id;
        $logistic->save();

        DB::commit();

        session()->flash('success', 'Logistic successfully updated');

        return redirect()->route('logistics');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $logistics = Logistic::find($id);

        if (!$logistics) return abort(404);

        DB::beginTransaction();

        Good::destroy($logistics->good_id);
        Expedition::destroy($logistics->expedition_id);
        $logistics->delete();

        DB::commit();

        session()->flash('success', 'logistic successfully deleted');

        return redirect()->route('logistics');
    }

    public function searchLogistics(Request $request): array
    {
        $data = [];
        $search = $request->q;
        $limit = $request->limit;

        $logistics = Logistic::query();
        if ($search) {
            $logistics->whereHas('goods', function ($query) use ($search) {
                foreach (explode(",", $search) as $key => $item) {
                    if ($key == 0) {
                        $query->where('name', 'LIKE', '%' . $item . '%');
                    } else {
                        $query->orWhere('name', 'LIKE', '%' . $item . '%');
                    }
                }
            });
        }

        $logistics = $logistics->paginate($limit ?? 10);
        foreach ($logistics as $item) {
            $data[] = [
                "id" => $item->id,
                "value" => @$item->goods->name
            ];
        }

        return $data;
    }
}

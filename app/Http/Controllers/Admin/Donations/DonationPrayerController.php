<?php

namespace App\Http\Controllers\Admin\Donations;

use App\Http\Controllers\Controller;
use App\Models\DonationPrayer;
use Illuminate\Http\Request;

class DonationPrayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prayers = DonationPrayer::whereHas('user')->latest()->get();
        return view('pages.prayers.index', compact('prayers'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prayer = DonationPrayer::find($id);

        if (!$prayer) return abort(404);

        $prayer->delete();

        session()->flash('success', 'Prayer successfully deleted');

        return redirect()->route('prayers');
    }
}

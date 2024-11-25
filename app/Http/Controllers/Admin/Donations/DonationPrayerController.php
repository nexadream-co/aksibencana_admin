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
        $prayers = DonationPrayer::latest()->get();
        return view('pages.prayers.index', compact('prayers'));
    }
}

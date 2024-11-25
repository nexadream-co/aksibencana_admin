<?php

namespace App\Http\Controllers\Admin\Donations;

use App\Http\Controllers\Controller;
use App\Models\Fundraiser;
use Illuminate\Http\Request;

class FundraiserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fundraisers = Fundraiser::latest()->get();
        return view('pages.fundraisers.index', compact('fundraisers'));
    }
}

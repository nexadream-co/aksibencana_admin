<?php

namespace App\Http\Controllers\Admin\Donations;

use App\Exports\DonationHistoryExport;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationHistory;
use Illuminate\Http\Request;

class DonationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $donation = Donation::find($id);

        if (!@$donation) return abort(404);

        $histories = DonationHistory::with(['user' => function($query) {
            $query->withTrashed();
        }])->where('donation_id', $id)->latest()->get();
        return view('pages.donations.histories.index', compact('histories', 'donation'));
    }

    /**
     * Download resource
     */
    public function download()
    {
        return (new DonationHistoryExport)->download("donation-".time().".xlsx");
    }
}

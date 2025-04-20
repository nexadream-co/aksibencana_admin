<?php

namespace App\Http\Controllers\API\Donations;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\DonationHistory;
use App\Models\DonationPrayer;
use App\Models\User;
use App\Notifications\DonationPaymentStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class DonationController extends Controller
{
    /**
     * List Donations
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
            'category_id' => ['integer'],
        ]);

        $results = [];
        $donations = Donation::where('status', 'active')->where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'));

        if ($request->category_id) {
            $donations->where('donation_category_id', $request->category_id);
        }

        $donations = $donations->paginate($request->limit ?? 10);

        foreach ($donations as $item) {
            $images = [];

            foreach (@json_decode($item->images) ?? [] as $row) {
                $images[] = url('storage') . '/' . $row;
            }

            $results[] = [
                "id" => $item->id,
                "title" => $item->title,
                "images" => $images,
                "category" => @$item->category ? [
                    "id" => $item->category->id,
                    "name" => $item->category->name,
                ] : null,
                "description" => $item->description,
                "status_donation" => $item->status,
                "total_donators" => (int) @$item->totalDonator ?? 0,
                "start_date" => $item->start_date,
                "end_date" => $item->end_date,
                "total_donation" => (int) @$item->totalDonation ?? 0,
                "target_donation" => (int) $item->target,
                "fundraiser" => @$item->fundraiser ? [
                    "id" => $item->fundraiser->id,
                    "name" => $item->fundraiser->name,
                    "photo" => @$item->fundraiser->photo ? url('storage') . '/' . @$item->fundraiser->photo : null,
                    "description" => @$item->fundraiser->description,
                ] : null,
                "created_at" => $item->created_at->format("Y-m-d h:i:s")
            ];
        }

        return response()->json([
            "message" => "Donations data successfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     * List Donation Categories
     */
    public function categories()
    {
        $results = [];
        $donation_categories = DonationCategory::orderBy('name', 'asc')->get();

        foreach ($donation_categories as $item) {
            $results[] = [
                "id" => $item->id,
                "name" => $item->name
            ];
        }

        return response()->json([
            "message" => "Donation categories data successfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     * Store Donation
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'amount' => ['required', 'integer'],
            'show_identity' => ['string'],
            'pray' => ['string'],
        ]);

        DB::beginTransaction();

        Configuration::setXenditKey(config('services.xendit.key'));

        $donation_history = new DonationHistory();
        $donation_history->nominal = $request->amount;
        $donation_history->user_id = $request->user()->id;
        $donation_history->donation_id = $id;
        $donation_history->status = 'unpaid';
        $donation_history->date = date('Y-m-d');
        $donation_history->save();

        $prayer = new DonationPrayer();
        $prayer->donation_history_id = $donation_history->id;
        $prayer->user_id = $request->user()->id;
        $prayer->show_identity = $request->show_identity ? true : false;
        $prayer->pray = $request->pray;
        $prayer->save();

        $createInvoice = new CreateInvoiceRequest([
            'external_id' => "$donation_history->id",
            'amount' => $request->amount,
            'payer_email' => $request->user()->email,
            'description' => "Pembayaran donasi",
            'invoice_duration' => 172800,
        ]);

        $apiInstance = new InvoiceApi();
        $generateInvoice = $apiInstance->createInvoice($createInvoice);

        $donation_history->snap_url = $generateInvoice['invoice_url'];
        $donation_history->save();

        DB::commit();

        return response()->json([
            "message" => "Donation successfully created, please do payment",
            "data" => [
                "snap_url" => $donation_history->snap_url
            ]
        ], 200);
    }

    /**
     * Handle Webhook
     * @unauthenticated
     */
    public function handleWebhook(Request $request)
    {
        $data = $request->all();
        $external_id = $data['external_id'];
        $status = strtolower($data['status']);
        $payment_method = $data['payment_method'];
        $payer_email = $data['payer_email'];

        DB::beginTransaction();

        $donation_history = DonationHistory::where('id', $external_id)->first();
        if (@$donation_history) {
            $donation_history->status = strtolower($status);
            $donation_history->payment_method = $payment_method;
            $donation_history->save();
        }

        DB::commit();

        try {
            $user = User::where('email', $payer_email)->first();
            if (@$user) {
                $user->notify(new DonationPaymentStatusUpdated($donation_history));
            }
        } catch (\Throwable $th) {
        }

        return response()->json([
            'message' => 'Webhook received',
            'status' => $status,
            'payment_method' => $payment_method,
        ], 200);
    }

    /**
     * Detail Donation
     */
    public function show(string $id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                "message" => "Donation data not found",
            ], 404);
        }

        $images = [];
        foreach (@json_decode($donation->images) ?? [] as $row) {
            $images[] = url('storage') . '/' . $row;
        }

        return response()->json([
            "message" => "Volunteer data successfully retrieved",
            "data" => [
                "id" => $donation->id,
                "title" => $donation->title,
                "category" => @$donation->category ? [
                    "id" => $donation->category->id,
                    "name" => $donation->category->name,
                ] : null,
                "images" => $images,
                "description" => $donation->description,
                "status_donation" => $donation->status,
                "total_donators" => (int) @$donation->totalDonator ?? 0,
                "start_date" => $donation->start_date,
                "end_date" => $donation->end_date,
                "total_donation" => (int) @$donation->totalDonation ?? 0,
                "target_donation" => (int) $donation->target,
                "fundraiser" => @$donation->fundraiser ? [
                    "id" => $donation->fundraiser->id,
                    "name" => $donation->fundraiser->name,
                    "photo" => @$donation->fundraiser->photo ? url('storage') . '/' . @$donation->fundraiser->photo : null,
                    "description" => @$donation->fundraiser->description,
                ] : null,
                "created_at" => $donation->created_at->format("Y-m-d h:i:s")
            ]
        ], 200);
    }

    /**
     * Donation Prayers
     */
    public function prayers(Request $request, string $id)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $prayers = DonationPrayer::whereHas('donationHistory', function ($query) use ($id) {
            $query->where('donation_id', $id);
        })->latest()->paginate($request->limit ?? 10);

        $results = [];

        foreach ($prayers as $item) {
            $results[] = [
                "id" => $item->id,
                "pray" => $item->pray,
                "show_identity" => @$item->show_identity ? true : false,
                "user" =>  [
                    "name" => @$item->user->name,
                    "photo_url" => @$item->user->photo_url,
                ],
                "created_at" => $item->created_at->format('Y-m-d H:i:s')
            ];
        }

        return response()->json([
            "message" => "Donation prayers data successfully retrieved",
            "data" => $results
        ], 200);
    }

    /**
     * Donation Histories
     */
    public function histories(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $histories = DonationHistory::where('user_id', $request->user()->id)->latest()->paginate($request->limit ?? 10);

        $results = [];

        foreach ($histories as $item) {
            $images = [];
            foreach (@json_decode(@$item->donation->images) ?? [] as $row) {
                $images[] = url('storage') . '/' . $row;
            }

            $results[] = [
                "id" => $item->id,
                "donation" => @$item->donation ? [
                    "id" => @$item->donation->id,
                    "title" => @$item->donation->title,
                    "description" => @$item->donation->description,
                    "images" => $images,
                ] : null,
                "total_donation" => (int) @$item->nominal ?? 0,
                "status" => $item->status,
                "snap_url" => $item->snap_url,
                "date" => $item->date,
                "created_at" => $item->created_at->format('Y-m-d H:i:s')
            ];
        }

        return response()->json([
            "message" => "Donation histories data successfully retrieved",
            "data" => $results
        ], 200);
    }
    
    /**
     * Donation All Histories
     */
    public function allHistories(Request $request, $id)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $histories = DonationHistory::where('donation_id', $id)->latest()->paginate($request->limit ?? 10);

        $results = [];

        foreach ($histories as $item) {
            $images = [];
            foreach (@json_decode(@$item->donation->images) ?? [] as $row) {
                $images[] = url('storage') . '/' . $row;
            }

            $show_user = @$item->prayer->show_identity;

            $results[] = [
                "id" => $item->id,
                "donation" => @$item->donation ? [
                    "id" => @$item->donation->id,
                    "title" => @$item->donation->title,
                    "description" => @$item->donation->description,
                    "images" => $images,
                ] : null,
                "user" => $show_user && @$item->user ? [
                    "id" => @$item->user->id,
                    "name" => @$item->user->name,
                    "email" => @$item->user->email,
                ] : null,
                "total_donation" => (int) @$item->nominal ?? 0,
                "status" => $item->status,
                "snap_url" => $item->snap_url,
                "date" => $item->date,
                "created_at" => $item->created_at->format('Y-m-d H:i:s')
            ];
        }

        return response()->json([
            "message" => "Donation all histories data successfully retrieved",
            "data" => $results
        ], 200);
    }
}

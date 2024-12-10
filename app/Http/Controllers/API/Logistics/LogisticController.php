<?php

namespace App\Http\Controllers\API\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Expedition;
use App\Models\Good;
use App\Models\Logistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogisticController extends Controller
{
    /**
     * List Logistics
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => ['integer'],
            'limit' => ['integer'],
        ]);

        $logistics = Logistic::paginate($request->limit ?? 10);

        $data = [];

        foreach ($logistics as $item) {
            $images = [];
            foreach (@json_decode(@$item->disaster->images) ?? [] as $row) {
                $images[] = url('storage') . '/' . $row;
            }

            $data[] = [
                "id" => $item->id,
                "disaster" => $item->disaster == null ? null : [
                    "id" => $item->disaster->id,
                    "title" => $item->disaster->title,
                    "category" => @$item->disaster->category == null ? null : [
                        "id" => $item->disaster->category->id,
                        "name" => $item->disaster->category->name,
                    ],
                    "images" => $images,
                    "description" => $item->disaster->description,
                    "date" => $item->disaster->date,
                    "status" => $item->disaster->status,
                    "address" => $item->disaster->address,
                    "created_by" => @$item->disaster->user == null ? null : [
                        "id" => @$item->disaster->user->id,
                        "name" => @$item->disaster->user->name
                    ]
                ],
                "branch_office" => @$item->branchOffice ? [
                    "id" => $item->branchOffice->id,
                    "name" => $item->branchOffice->name,
                ] : null,
                "date" => $item->date,
                "status" => $item->status,
                "created_at" => $item->created_at->format('Y-m-d H:i:s')
            ];
        }

        return response()->json([
            "message" => "Logistics data successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * Store Logistic
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_office_id' => ['required', 'integer'],
            'disaster_id' => ['required', 'integer'],
            'district_id' => ['required', 'integer'],
            'origin_address' => ['required', 'string'],
            'telp' => ['required', 'string'],
            'expedition_name' => ['required', 'string'],
            'weight' => ['required', 'string'],
            'image' => ['required', 'string'],
            'date' => ['required', 'string'],
            'receipt_number' => ['required', 'string'],
            'sender_name' => ['required', 'string'],
            'goods.name' => ['required', 'string'],
            'goods.type' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        $logistic = new Logistic();
        $logistic->user_id = $request->user()->id;
        $logistic->disaster_id = $request->disaster_id;
        $logistic->date = $request->date;
        $logistic->image = @$request->image ? str_replace(url('storage') . '/', '', $request->image) : null;
        $logistic->branch_office_id = $request->branch_office_id;
        $logistic->status = 'request';

        $goods = new Good();
        $goods->name = $request->goods['name'];
        $goods->type = $request->goods['type'];
        $goods->save();

        $logistic->good_id = $goods->id;

        $expedition = new Expedition();
        $expedition->district_id = $request->district_id;
        $expedition->name = $request->expedition_name;
        $expedition->sender_name = $request->sender_name;
        $expedition->origin_address = $request->origin_address;
        $expedition->telp = $request->telp;
        $expedition->weight = $request->weight;
        $expedition->delivered_at = $request->date;
        $expedition->receipt_code = $request->receipt_number;
        $expedition->save();

        $logistic->expedition_id = $expedition->id;
        $logistic->save();

        DB::commit();

        return response()->json([
            "message" => "Your logistic successfully created, please wait we will confirm your logistics"
        ], 200);
    }

    /**
     * Detail Logistic
     */
    public function show(string $id)
    {
        $logistic = Logistic::find($id);

        if (!$logistic) {
            return response()->json([
                "message" => "Logistic data not found",
            ], 404);
        }

        $images = [];
        foreach (@json_decode($logistic->images) ?? [] as $row) {
            $images[] = url('storage') . '/' . $row;
        }

        $data = [
            "id" => $logistic->id,
            "disaster" => $logistic->disaster == null ? null : [
                "id" => $logistic->disaster->id,
                "title" => $logistic->disaster->title,
                "category" => @$logistic->disaster->category == null ? null : [
                    "id" => $logistic->disaster->category->id,
                    "name" => $logistic->disaster->category->name,
                ],
                "images" => $images,
                "description" => $logistic->disaster->description,
                "date" => $logistic->disaster->date,
                "status" => $logistic->disaster->status,
                "address" => $logistic->disaster->address,
                "created_by" => @$logistic->disaster->user == null ? null : [
                    "id" => @$logistic->disaster->user->id,
                    "name" => @$logistic->disaster->user->name
                ]
            ],
            "branch_office" => @$logistic->branchOffice ? [
                "id" => $logistic->branchOffice->id,
                "name" => $logistic->branchOffice->name,
            ] : null,
            "goods" => @$logistic->goods ? [
                "name" => @$logistic->goods->name,
                "type" => @$logistic->goods->type,
            ] : null,
            "image" => @$logistic->image ? url('storage') . '/' . @$logistic->image : null,
            "date" => $logistic->date,
            "receipt_number" => $logistic->expedition->receipt_code,
            "status" => $logistic->status,
            "sender_name" => @$logistic->expedition->sender_name,
            "created_at" => $logistic->created_at->format('Y-m-d H:i:s')
        ];

        return response()->json([
            "message" => "Detail logistic successfully retrieved",
            "data" => $data,
        ], 200);
    }

    /**
     * Logistic Receipt Track
     */
    public function receipts($id)
    {
        $logistic = Logistic::find($id);
        if (!@$logistic) {
            return response()->json([
                "message" => "Logistic not found"
            ], 404);
        }

        if (!@$logistic->expedition) {
            return response()->json([
                "message" => "Expedition not found"
            ], 404);
        }

        $url = config('rajaongkir.URL') . '/waybill';
        $key = config('rajaongkir.KEY');

        $result = null;

        try {
            ini_set('max_execution_time', 100000);

            // Header untuk API RajaOngkir
            $headers = [
                'key: ' . $key,
                'Content-Type: application/x-www-form-urlencoded',
            ];

            // Data POST
            $postData = [
                'waybill' => @$logistic->expedition->receipt_code,
                'courier' => @$logistic->expedition->name,
            ];

            // Panggil fungsi getCURL
            $result = $this->getCURL($url, $headers, $postData);
        } catch (\Throwable $th) {
        }

        $resultData = [];
        $data = @$result['rajaongkir']['result']['manifest'] ?? [];
        foreach ($data as $item) {
            $resultData[] = [
                "title" => @$item["manifest_description"],
                "date" => @$item["manifest_date"]
            ];
        }

        return response()->json([
            "message" => "Logistic receipt tracks successfully retrieved",
            "data" => $resultData,
        ], 200);
    }

    public function getCURL($url, $headers = [], $postData = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Tambahkan header jika diberikan
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        // Jika ada data POST, gunakan metode POST
        if (!empty($postData)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData)); // x-www-form-urlencoded
        }

        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }
}

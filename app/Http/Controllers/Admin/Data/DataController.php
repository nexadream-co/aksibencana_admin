<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = null;

        try {
            ini_set('max_execution_time', 100000);
            $response = $this->getCURL(
                'https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json'
            );
        } catch (\Throwable $_) {
        }

        $earthquakes = @$response['Infogempa']['gempa'];

        return view('pages.data.index', compact('earthquakes'));
    }

    public function getCURL($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }
}

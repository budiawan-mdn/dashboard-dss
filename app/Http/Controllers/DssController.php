<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class DssController extends Controller
{
    public function getPrediction(Request $request) {
    // 1. Ambil data dari input form pengguna
    $payload = [
        'latitude' => $request->lat,
        'longitude' => $request->lng,
        'bobot_air' => $request->bobot_air,
        'bobot_tanah' => $request->bobot_tanah
    ];

    // 2. Panggil ML Engine (Python FastAPI) yang sudah kita buat tadi
    $response = Http::post('http://127.0.0.1:8000/v1/predict', $payload);

    // 3. Kembalikan hasilnya ke Frontend (Dashboard)
    return $response->json();
}
}

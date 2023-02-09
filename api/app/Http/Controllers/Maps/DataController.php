<?php

namespace App\Http\Controllers\Maps;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DataController extends Controller {
    
    public function getDevicesMap() {
        
        $geoData = DB::table('x_geo_declare')
        ->get();

        $data = DB::table('x_devices')
        ->get();

        return response()->json([
            'geodata' => $geoData,
            'data' => $data,
        ], 200);
    }
}
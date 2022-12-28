<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class LocationRelayController extends Controller {

    public function index(Request $request) {
        $imei = $request->input('imei');
        $from = $request->input('from');
        $to = $request->input('to');
        
        // if ($from || $to) {
        $this->validate($request, [
            'imei' => 'required',
            'from' => 'required|date_format:Y-m-d H:i:s',
            'to' => 'required|date_format:Y-m-d H:i:s',
        ]);
        $data = DB::table('loc_relay')
        ->where('imei','=',$imei)
        ->whereBetween('time', [$from, $to])
        ->orderBy('time','asc')
        ->get();
        $totalData = DB::table('loc_relay')
        ->where('imei','=',$imei)
        ->whereBetween('time', [$from, $to])
        ->orderBy('time','asc')
        ->count();
        // }else{
        //     $data = DB::table('loc_relay')
        //     ->where('imei','=',$imei)
        //     ->orderBy('time','asc')
        //     ->get();
        //     $totalData = DB::table('loc_relay')
        //     ->where('imei','=',$imei)
        //     ->orderBy('time','asc')
        //     ->count();
        // }

        
        return response()->json([
            'total' => $totalData,
            'data' => $data,
        ], 200);
    }

    public function latest_loc_relay(Request $request) {
        
        $imei = $request->input('imei');
        if (!$imei) {
            return response()->json([
                'msg' => 'Data not found.'
            ], 404);
        }
        $data = DB::table('loc_relay')
        ->where('imei','=', $imei)
        ->orderBy('time','desc')
        ->first();
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function deviceList() {
        $data = DB::table('loc_relay')
        ->select('imei')
        ->groupBy('imei')
        ->get();
        foreach ($data as $key => $value) {
            $value->latestData = DB::table('loc_relay')
            ->selectRaw('seq, "time", event, "long", lat, pdop, direct, speed, bat, sat')
            ->orderBy('time','desc')
            ->first();
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function trackinglogs(Request $request) {
        $imei = $request->input('imei');
        if (!$imei) {
            return response()->json([
                'data' => []
            ], 200);
        };
        $data = DB::table('loc_relay')
        ->where('imei','=',$imei)
        ->orderBy('time','desc')
        ->get();
        return response()->json([
            'data' => $data
        ], 200);
    }

}
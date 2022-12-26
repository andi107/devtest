<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class LocationRelayController extends Controller {

    public function index(Request $request) {
        //2018-01-01 00:00:00
        $from = $request->input('from');
        $to = $request->input('to');

        if ($from || $to) {
            $this->validate($request, [
                'from' => 'required|date_format:Y-m-d H:i:s',
                'to' => 'required|date_format:Y-m-d H:i:s',
            ]);
            $data = DB::table('loc_relay')
            ->whereBetween('time', [$from, $to])
            ->orderBy('time','asc')
            ->get();
            $totalData = DB::table('loc_relay')
            ->whereBetween('time', [$from, $to])
            ->orderBy('time','asc')
            ->count();
        }else{
            $data = DB::table('loc_relay')
            ->orderBy('time','asc')
            ->get();
            $totalData = DB::table('loc_relay')
            ->orderBy('time','asc')
            ->count();
        }

        
        return response()->json([
            'total' => $totalData,
            'data' => $data,
        ], 200);
    }

    public function latest_loc_relay(Request $req) {
        
        $sq = $req->input('type');
        if (!$sq) {
            return response()->json([
                'msg' => 'Data not found.'
            ], 404);
        }

        if ($sq == 'LOCATION_RELAY') {
            $data = DB::table('loc_relay')
            ->orderBy('time','desc')
            ->first();
        }else{
            return response()->json([
                'msg' => 'Data not found.'
            ], 404);
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

}
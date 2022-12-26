<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class LocationRelayController extends Controller {

    public function index() {
        $data = DB::table('loc_relay')
        ->orderBy('time','asc')
        // ->skip(0)->take(50)
        ->get();
        return response()->json([
            'data' => $data
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
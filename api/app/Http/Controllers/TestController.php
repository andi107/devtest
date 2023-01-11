<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class TestController extends Controller {

    function index(Request $request) {
        $deviceId = $request->input('deviceId');
        $time = $request->input('time');
        $seqNumber = $request->input('seqNumber');
        $resHex = $request->input('data');
        // dd(substr($resHex,11,7));
        
        $typeId = substr($resHex,0,2);
        $tmpHx['hexmsg'] = $resHex;
        switch ($typeId) {
            case '0f':
                $tmpHx['event'] = 'OBU Wakeup';
                $tmpHx = $this->h0f($tmpHx,$resHex);
                break;
            case '2f':
                $tmpHx['event'] = 'GPS Potition in Toll Way';
                $tmpHx = $this->h2f($tmpHx,$resHex);
                break;
            case '3f':
                $tmpHx['event'] = 'BLE Beacon + GNSS Position';
                $tmpHx = $this->h3f($tmpHx,$resHex);
                break;
            default:
                break;
        }
        
        // dd($tmpHx);
        $res = json_encode($tmpHx);
        return response()->json([
            'data' => json_decode($res)
        ], 200);
    }

}
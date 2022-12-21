<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class RcvLogController extends Controller {

    public function save(Request $req) {
        $this->validate($req, [
            'id' => 'required|numeric',
            'seq' => 'required',
            'time' => 'required',
            'imei' => 'required',
            'event' => 'required',
            'power' => 'required',
            'bat' => 'required',
            'sig' => 'required',
            'sat' => 'required',
        ]);

        $id = $req->input('id');
        $seq = $req->input('seq');
        $time = $req->input('time');
        $imei = $req->input('imei');
        $event = $req->input('event');
        $power = $req->input('power');
        $bat = $req->input('bat');
        $sig = $req->input('sig');
        $sat = $req->input('sat');

        $chkData = DB::table('rcv_ignition2')
        ->where('id','=', $id)
        ->first();
        if (!$chkData) {
            DB::table('rcv_ignition2')
            ->insert([
                'id' => $id,
                'seq' => $seq,
                'time' => $time,
                'imei' => $imei,
                'event' => $event,
                'power' => $power,
                'bat' => $bat,
                'sig' => $sig,
                'sat' => $sat,
            ]);
        }else{
            DB::table('rcv_ignition2')
            ->where('id','=', $id)
            ->update([
                'seq' => $seq,
                'time' => $time,
                'imei' => $imei,
                'event' => $event,
                'power' => $power,
                'bat' => $bat,
                'sig' => $sig,
                'sat' => $sat,
            ]);
        }

        
        $data = DB::table('rcv_ignition2')
        ->where('id','=', $id)
        ->first();
        return response()->json([
            'msg' => 'Data OK.',
            'data' => $data
        ], 200);
    }


}
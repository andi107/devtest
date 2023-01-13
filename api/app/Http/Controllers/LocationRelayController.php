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
    // select entry_id,exit_id  from v_tolgate vt where entry_imei = '866782042270062'
    // order by entry_id desc limit 1

    public function currentRun(Request $request) {
        $imei = $request->input('imei');
        $tolgate = DB::table('v_tolgate')
        ->where('entry_imei','=', $imei)
        ->whereNull('exit_id')
        ->orderBy('entry_time','desc')
        ->first();
        if ($tolgate) {
            $status = 0; //incomplete
            $data = DB::table('loc_relay')
            ->where('imei','=', $imei)
            ->orderBy('time','desc')
            ->first();
        }else{
            $status = 1; //complete
            $data = null;
        }
        return response()->json([
            'status' => $status,
            'data' => $data,
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
            if (DB::table('v_tolgate')->where('entry_imei','=', $value->imei)->whereNull('exit_id')->first()) {
                $value->status = 'INCOMPLETE';
            }else{
                $value->status = 'COMPLETE';
            }
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function trackinglogs(Request $request) {
        $imei = $request->input('imei');
        $from = $request->input('from');
        $to = $request->input('to');
        
        $this->validate($request, [
            'imei' => 'required',
            'from' => 'required|date_format:Y-m-d H:i:s',
            'to' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $data = DB::table('loc_relay')
        ->where('imei','=',$imei)
        ->whereBetween('time', [$from, $to])
        ->orderBy('time','desc')
        ->get();
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function dasboardChart(Request $request) {
        $imei = $request->input('imei');
        $timeframe = $request->input('timeframe');
        $dtnow = Carbon::now();
        // $dtnow = Carbon::parse('2022-12-24');
        $tmpBat = [];
        $tmpSpeed = [];
        switch ($timeframe) {
            case '1D':
                $dtnow = $dtnow->format('Y-m-d');
                for($i = 0; $i<=23; $i++) {
                    // $sum = $sum + $i;
                    $between = [
                        $dtnow.' '.$this->getZero($i).':00:00',
                        $dtnow.' '.$this->getZero($i+1).':00:00'
                    ];
                    $data = DB::table('loc_relay')
                    ->selectRaw('avg(CAST(bat AS decimal)) as agg')
                    ->where('imei','=',$imei)
                    ->whereBetween('time', $between)
                    ->first();
                    if ($data->agg >= '0'){
                        $tmpBat[$i] = number_format($data->agg,2);
                    }else if ($data->agg == null) {
                        try {
                            $tmpBat[$i] = $tmpBat[$i-1];
                        } catch (\Throwable $th) {
                            $tmpBat[$i] = "0";
                        }
                    }
                    $data = DB::table('loc_relay')
                    ->selectRaw('avg(CAST(speed AS decimal)) as agg')
                    ->where('imei','=',$imei)
                    ->whereBetween('time', $between)
                    ->first();
                    if ($data->agg >= '0'){
                        $tmpSpeed[$i] = number_format($data->agg,2);
                    }else if ($data->agg == null) {
                        try {
                            $tmpSpeed[$i] = $tmpSpeed[$i-1];
                        } catch (\Throwable $th) {
                            $tmpSpeed[$i] = "0";
                        }
                    }
                }
                break;
            case '1W':
                // $dtnow = $dtnow->format('Y-m-d');

                // $weekStartDate = $dtnow->copy()->startOfWeek()->format('Y-m-d');
                // $weekEndDate = $dtnow->copy()->endOfWeek()->format('Y-m-d');
                // $start = $dtnow->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
                // $end = $dtnow->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                // Carbon::now()->subDays(30)->format('Y-m-d')
                for($i = 0; $i<=6; $i++) {
                    $getDay = $dtnow->copy()->startOfWeek($i)->format('Y-m-d');
                    // $getDay = $dtnow->startOfWeek($i)->format('Y-m-d');
                    
                    $between = [
                        $getDay.' 00:00:00',
                        $getDay.' 23:59:59',
                    ];
                    // dd($dtnow->format('Y-m-d'),$getDay);
                    $data = DB::table('loc_relay')
                    ->selectRaw('avg(CAST(bat AS decimal)) as agg')
                    ->where('imei','=',$imei)
                    ->whereBetween('time', $between)
                    ->first();
                    if ($data->agg >= '0'){
                        $tmpBat[$i] = number_format($data->agg,2);
                    }else if ($data->agg == null) {
                        try {
                            $tmpBat[$i] = $tmpBat[$i-1];
                        } catch (\Throwable $th) {
                            $tmpBat[$i] = "0";
                        }
                    }
                    $data = DB::table('loc_relay')
                    ->selectRaw('avg(CAST(speed AS decimal)) as agg')
                    ->where('imei','=',$imei)
                    ->whereBetween('time', $between)
                    ->first();
                    if ($data->agg >= '0'){
                        $tmpSpeed[$i] = number_format($data->agg,2);
                    }else if ($data->agg == null) {
                        try {
                            $tmpSpeed[$i] = $tmpSpeed[$i-1];
                        } catch (\Throwable $th) {
                            $tmpSpeed[$i] = "0";
                        }
                    }
                }
                // dd($weekStartDate,$weekEndDate,$start,$end);
            default:
                break;
        }
        
        
        return response()->json([
            'dataBat' => $tmpBat,
            'dataSpeed' => $tmpSpeed
        ], 200);
    }

    function getZero($intVal) {
        return str_pad($intVal, 2, '0', STR_PAD_LEFT);
    }

    public function ignitionData(Request $request) {

        $imei = $request->input('imei');
        $data = DB::table('ignition2')
        ->where('imei','=', $imei)
        ->orderBy('time','desc')
        ->first();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function geoDeclareList(Request $request) {

        $imei = $request->input('imei');

        $data = DB::table('v_tolgate')
        ->where('entry_imei','=', $imei)
        ->get();
        
        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function routeInGeo(Request $request) {
        $imei = $request->input('imei');
        $geoid = $request->input('geoid');

        $data = DB::table('loc_relay')
        ->selectRaw('id, "time", "event", long, lat, pdop, direct, speed, bat, sat, alt')
        ->where('imei','=', $imei)
        ->where('geoid','=',$geoid)
        ->orderBy('time', 'asc')
        ->get();

        $totalData = DB::table('loc_relay')
        ->where('imei','=', $imei)
        ->where('geoid','=',$geoid)
        ->orderBy('time', 'asc')
        ->count();
        
        return response()->json([
            'total' => $totalData,
            'data' => $data,
        ], 200);
    }

    public function geoDeclareLts(Request $request) {
        $imei = $request->input('imei');

        $data = DB::table('v_tolgate')
        ->where('entry_imei','=', $imei)
        ->orderBy('entry_time','desc')
        ->first();
        
        return response()->json([
            'data' => $data,
        ], 200);
    }
}
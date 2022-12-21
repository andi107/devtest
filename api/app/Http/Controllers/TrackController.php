<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class TrackController extends Controller {

    public function status(Request $req) {
        
        $sq = $req->input('sq');
        if (!$sq) {
            return response()->json([
                'msg' => 'Data not found.'
            ], 404);
        }

        $data = DB::table('datar1')
        ->where('SEQUENNCE','=', $sq)
        // ->orderBy('DATE','desc')
        ->orderBy(DB::raw("str_to_date(date, '%d/%m/%Y')"), 'DESC')
        ->orderBy('TIME','desc')
        ->first();
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function map(Request $req) {
        $sq = $req->input('sq');
        $datefilter = $req->input('datefilter');
        if (!$sq) {
            return response()->json([
                'msg' => 'Data not found.'
            ], 404);
        }else if($datefilter) {
            $data = DB::table('datar1')
            ->where('SEQUENNCE','=', $sq)
            ->where('DATE','=',$datefilter)
            ->orderBy(DB::raw("str_to_date(date, '%d/%m/%Y')"), 'ASC')
            ->orderBy('TIME','asc')
            ->get();
        }else{
            $data = DB::table('datar1')
            ->where('SEQUENNCE','=', $sq)
            ->orderBy(DB::raw("str_to_date(date, '%d/%m/%Y')"), 'ASC')
            ->orderBy('TIME','asc')
            // ->skip(0)->take(20)
            ->get();
        }

        $tmpInt = 0;
        foreach ($data as $bvalue) {
            $tmpInt++;
        }

        return response()->json([
            'count' => $tmpInt,
            'data' => $data,
        ], 200);
    }

    public function log(Request $req) {
        $sq = $req->input('sq');
        $datefilter = $req->input('datefilter');
        if (!$sq) {
            return response()->json([
                'msg' => 'Data not found.'
            ], 404);
        }else if($datefilter) {
            $data = DB::table('datar1')
            ->where('SEQUENNCE','=', $sq)
            ->where('DATE','=',$datefilter)
            ->orderBy(DB::raw("str_to_date(date, '%d/%m/%Y')"), 'desc')
            ->orderBy('TIME','desc')
            ->get();
        }else{
            $data = DB::table('datar1')
            ->where('SEQUENNCE','=', $sq)
            ->orderBy(DB::raw("str_to_date(date, '%d/%m/%Y')"), 'desc')
            ->orderBy('TIME','desc')
            ->get();
        }

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function dummyTrack() {

        $dtnow = Carbon::now();
        
        $data = DB::table('dummytrack')
        ->orderBy('id','asc')
        ->first();

        if ($data) {

            $saveId = DB::table('datar1')
            ->insertGetId([
                'ID' => rand(500,1000),
                'SEQUENNCE' => 808080,
                'DATE' => Carbon::parse($dtnow)->format('d/m/Y'),
                'TIME' => $dtnow->toTimeString(),
                'IMEI' => '866782042270062 OK', 
                'EVENT' => 'R1', 
                'LONGITUDE' => $data->fnlng, 
                'LATITUDE' => $data->fnlat, 
                'DIRECT' => $data->fnhead, 
                'SPEED' => rand(0,80), 
                'BATTERY' => rand(5,100),
                'SATELITE' => rand(3,9)
            ]);

            DB::table('dummytrack')
            ->where('id','=', $data->id)
            ->delete();
        }

        return response()->json([
            'data' => $data
        ], 200);
    }

}
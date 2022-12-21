<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class RcvLogController extends Controller {

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


}
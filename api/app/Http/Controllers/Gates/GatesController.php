<?php

namespace App\Http\Controllers\Gates;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class GatesController extends Controller {
    
    public function list() {

        $data = DB::table('x_geo_declare')
        ->orderBy('id','asc')
        ->get();
        foreach ($data as $a_value) {
            foreach ($a_value as $akey => $avalue) {
                if ($akey == 'id') {
                    $_polyData = DB::table('x_geo_declare_det')
                    ->where('x_geo_declare_id','=', $avalue)
                    ->where('fnchkpoint','=', 0)
                    ->get();
                    $a_value->polygonData = $_polyData;
                    if (count($_polyData) != 0) {
                        $a_value->type = 'Polygon';
                    }else{
                        $a_value->type = '';
                    }
                }
            }
        }

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function detail(Request $request) {
        $gateid = $request->input('gateid');
        $data = DB::table('x_geo_declare')
        ->where('id','=', $gateid)
        ->orderBy('id','asc')
        ->first();
        if ($data) {
            $_polyData = DB::table('x_geo_declare_det')
            ->where('x_geo_declare_id','=', $data->id)
            ->where('fnchkpoint','=', 0)
            ->get();
            $data->polygonData = $_polyData;
            if (count($_polyData) != 0) {
                $data->type = 'Polygon';
            }else{
                $data->type = '';
            }
        }
        return response()->json([
            'data' => $data,
        ], 200);
    }

}
<?php

namespace App\Http\Controllers\Maps;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DebuggingRouteController extends Controller {
    
    public function list(Request $request) {
        $this->validate($request, [
            'did' => 'required',
            'from' => 'required|date_format:Y-m-d H:i:s',
            'to' => 'required|date_format:Y-m-d H:i:s',
        ]);
        $did = $request->input('did');
        $from = $request->input('from');
        $to = $request->input('to');

        $routes = DB::table('debuging_routes')
        ->where('ftdevice_id','=',$did)
        ->whereBetween('created_at', [$from, $to])
        ->get();

        return response()->json([
            'data' => $routes,
        ], 200);
    }
}
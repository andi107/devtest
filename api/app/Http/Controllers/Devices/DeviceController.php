<?php

namespace App\Http\Controllers\Devices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DeviceController extends Controller {
    
    public function index() {

        $data = DB::table('x_obu_devices')
        ->orderBy('created_at','desc')
        ->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function create(Request $request) {

        $this->validate($request, [
            'imei' => 'required|max:100',
            'name' => 'required|max:100',
            'type' => 'max:100',
            'description' => 'max:255'
        ]);
        $imei = $request->input('imei');
        $name = $request->input('name');
        $type = $request->input('type');
        $description = $request->input('description');
        DB::beginTransaction();
        try {
            $dtnow = Carbon::now();

            $chkData = DB::table('x_obu_devices')
            ->where('ftimei','=', $imei)
            ->first();
            $chkName = DB::table('x_obu_devices')
            ->where('ftname','=', $name)
            ->first();
            if ($chkData) {
                return response()->json([
                    'msg' => $imei. ' already exists.',
                ], 442);
            }else if ($chkName) {
                return response()->json([
                    'msg' => $name. ' already exists.',
                ], 442);
            }
            
            DB::table('x_obu_devices')
            ->insert([
                'ftimei' => $imei,
                'ftname' => $name,
                'fttype' => $type,
                'ftdescriptio' => $description,
                'created_at' => $dtnow,
                'updated_at' => $dtnow
            ]);

            $data = DB::table('x_obu_devices')
            ->where('ftimei','=', $imei)
            ->first();

            DB::commit();
            return response()->json([
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => 'Data not found',
            ], 404);
        }
    }

    public function update(Request $request) {

        $this->validate($request, [
            'imei' => 'required|max:100',
            'name' => 'required|max:100',
            'type' => 'max:100',
            'description' => 'max:255'
        ]);
        $imei = $request->input('imei');
        $name = $request->input('name');
        $type = $request->input('type');
        $description = $request->input('description');
        DB::beginTransaction();
        // try {
            $dtnow = Carbon::now();

            $chkData = DB::table('x_obu_devices')
            ->where('ftimei','=', $imei)
            ->first();
            $chkName = DB::table('x_obu_devices')
            ->where('ftimei','<>', $imei)
            ->where('ftname','=', $name)
            ->first();
            if (!$chkData) {
                return response()->json([
                    'msg' => $imei. ' not found.',
                ], 442);
            }else if ($chkName) {
                return response()->json([
                    'msg' => $name. ' already exists.',
                ], 442);
            }
            
            DB::table('x_obu_devices')
            ->where('ftimei','=', $imei)
            ->update([
                'ftimei' => $imei,
                'ftname' => $name,
                'fttype' => $type,
                'ftdescriptio' => $description,
                'updated_at' => $dtnow
            ]);

            $data = DB::table('x_obu_devices')
            ->where('ftimei','=', $imei)
            ->first();

            DB::commit();
            return response()->json([
                'data' => $data,
            ], 200);
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return response()->json([
        //         'data' => 'Data not found',
        //     ], 404);
        // }
    }

    public function list() {
        $data = DB::table('x_obu_devices')
        ->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

}
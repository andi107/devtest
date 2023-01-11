<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected function h0f($tmpHx,$resHex) {
        // 0f.00.26.ffffffffffffffffff
        try {
            $powerStatus = substr($resHex,2,2);
            if ($powerStatus == '00') {
                $tmpHx['power_status'] = 'Device ON by USB Power';
            }else{
                $tmpHx['power_status'] = 'Unknown Status';
            }
            
            $tmpHx['bat_level'] = hexdec(substr($resHex,4,2));
            return $tmpHx;
        } catch (\Throwable $th) {
            return 'err 0f | '. $resHex;
        }
    }

    protected function h2f($tmpHx,$resHex) {
        // 2f,8,05f359b,0,65deccf,05,0131
        try {
            $p1 = substr($resHex,2,1);
            $p2 = substr($resHex,10,1);
            if ($p1 == 8) {
                $tmpHx['lat'] = (hexdec(substr($resHex,3,7)) / 1000000) * -1;
            }else if($p1 == 0) {
                $tmpHx['lon'] = (hexdec(substr($resHex,3,7)) / 1000000) * 1;
            }
            if ($p2 == 8) {
                $tmpHx['lat'] = (hexdec(substr($resHex,11,7)) / 1000000) * -1;
            }else if($p2 == 0) {
                $tmpHx['lon'] = (hexdec(substr($resHex,11,7)) / 1000000) * 1;
            }
            $unk1 = substr($resHex,18,2);
            $unk2 = substr($resHex,20,4);
            $tmpHx['unknownVal1'][$unk1] = hexdec($unk1);
            $tmpHx['unknownVal2'][$unk2] = hexdec($unk2);
            
            return $tmpHx;
        } catch (\Throwable $th) {
            return 'err 2f'. $resHex;
        }
    }

    protected function h3f($tmpHx,$resHex) {
        // 3f.56ce.805f49b0.065d939a.a7

        return $tmpHx;
    }

}

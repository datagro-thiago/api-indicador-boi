<?php

namespace Src\Dominio\Utils;

class DataUtils
{
    public static function consertaData($dh)
    {
        if (strlen($dh) < 10)
            $dh = date("Y-m-d 00:00:00");
        else
            if (
                is_numeric($dh[0]) and is_numeric($dh[1]) and is_numeric($dh[2]) and is_numeric($dh[3]) and
                ($dh[4] == '-' or $dh[4] == '/') and
                is_numeric($dh[5]) and is_numeric($dh[6]) and
                ($dh[7] == '-' or $dh[7] == '/') and
                is_numeric($dh[8]) and is_numeric($dh[9])
            ) {
                // data ok
                $dh[4] = '-';
                $dh[7] = '-';
            } else
                if (
                    is_numeric($dh[0]) and is_numeric($dh[1]) and
                    ($dh[2] == '-' or $dh[2] == '/') and
                    is_numeric($dh[3]) and is_numeric($dh[4]) and
                    ($dh[5] == '-' or $dh[5] == '/') and
                    is_numeric($dh[6]) and is_numeric($dh[7]) and is_numeric($dh[8]) and is_numeric($dh[9])
                ) {
                    // 11/11/2022 00:13:15
                    $dh = substr($dh, 6, 4) . "-" . substr($dh, 3, 2) . "-" . substr($dh, 0, 2) . substr($dh, 10);
                }

        return $dh;
    }

    public static function ConverterParaString(\DateTime $data): string {

        try {
            return $data->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null; 
        }
        
    }

}

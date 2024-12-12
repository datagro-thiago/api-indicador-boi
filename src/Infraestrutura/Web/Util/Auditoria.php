<?php

namespace Src\Infraestrutura\Web\Util;

class Auditoria {

    public static function audit($ini0, $api_version): array {
        return [
            'time' => number_format (microtime (true) - $ini0, 3),
            'hora' => substr (date ("l"), 0, 3) . date (" Y-m-d H:i:s"),
            'version' => $api_version,
        ];
    
    }
}
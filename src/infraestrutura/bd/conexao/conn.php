<?php

namespace Src\Infraestrutura\Bd\Conexao;

class Conexao {

    public static function conn (): \mysqli {
        $servidor = "dev.clwgf4bi7gwy.sa-east-1.rds.amazonaws.com";
		$porta = 6066;
		$nome = "tvieira";
		$senha = "j6ZFgBg9Bla4";
		$base = "indicadordoboi";

        $mysqli = new \mysqli ($servidor, $nome, $senha, $base, $porta);

        if (mysqli_connect_errno())
			trigger_error(mysqli_connect_error());

        $mysqli->set_charset("utf8");
        return $mysqli;
		}
}
<?php

namespace Src\Infraestrutura\Bd\Conexao;

use mysqli;

class Conexao {

    public static function conn(): mysqli {
        $servidor = \getenv("DB_HOST");
        $porta = \getenv("DB_PORT");
        $nome = \getenv("DB_USER");
        $senha = \getenv("DB_PASSWORD");
        $base = \getenv("DB_NAME");

        $mysqli = new mysqli(
            $servidor,
             $nome,
              $senha,
               $base,
                $porta
            );

        if ($mysqli->connect_error) {
            throw new \RuntimeException(sprintf(
                "Falha na conexão com o banco de dados (%s:%s): %s",
                $servidor,
                $porta,
                $mysqli->connect_error
            ));
        }
        $mysqli->set_charset("utf8mb4");
        
        return $mysqli;
    }
}

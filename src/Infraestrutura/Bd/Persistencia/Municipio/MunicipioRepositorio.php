<?php

namespace Src\Infraestrutura\Bd\Persistencia\Municipio;

use Src\Dominio\Municipio\Municipio;
use Src\Dominio\Municipio\MunicipioGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class MunicipioRepositorio implements MunicipioGateway {

    private Conexao $con ;

    public function __construct() {
        $this->con = new Conexao();
    }

    public function Buscar (Municipio $municipio) {
        $resultado = [];

        $itemParaPesquisa = "%" . $municipio->getNome() . "%";

        $q = "SELECT * FROM " . $municipio->getNomeTabela() ." WHERE nome LIKE ?";

        $result = $this->con->conn()->execute_query($q, [
            $itemParaPesquisa
        ]);

                    // Obtenha os resultados
            $objeto = $result->fetch_object();
            $array = json_decode(json_encode($objeto), true);
            // Libera os recursos
            $result->close();
            $resultado = [
                "id" => $array["cod"],
                "nome" => $array["nome"]
            ];
        

        return $resultado;
    }
}
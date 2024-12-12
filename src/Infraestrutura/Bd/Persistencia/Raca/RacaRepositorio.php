<?php

namespace Src\Infraestrutura\Bd\Persistencia\Raca;

use Src\Dominio\Raca\Raca;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class RacaRepositorio {

    private Conexao $con;

    public function __construct()
    {
        $this->con = new Conexao();
    }

    public function buscar(string $nome) {
        
        $q = "SELECT * FROM " . Raca::getNomeTabela() . " WHERE JSON_CONTAINS (aliases, '\"" . $nome . "\"');";
        $set = $this->con->conn()->execute_query($q);

        if ($set) {
            while ($reg = $set->fetch_assoc()) {
                $tab[] = $reg; 
            }
        }

        return $tab[0];
    }

    public function buscarTodas(): array
    {

        $categorias = [];
        $query = "SELECT id, nome, aliases FROM " . Raca::getNomeTabela() . ";";
        $resultado = $this->con->conn()->query($query);

        if (!$resultado) {
            throw new \Exception("Erro ao buscar categorias: " . $this->con->conn()->error);
        }

        $categorias = [];
        if ($resultado) {
            while ($reg = $resultado->fetch_assoc()) {
                $categorias[] = $reg;  
            }
        }

        return $categorias;
    }

}
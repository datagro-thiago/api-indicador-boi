<?php

namespace Src\Infraestrutura\Bd\Persistencia\Categoria;

use Src\Dominio\Categoria\Categoria;
use Src\Dominio\Categoria\CategoriaGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class CategoriaRepositorio implements CategoriaGateway
{

    private Conexao $con;

    public function __construct()
    {
        $this->con = new Conexao();

    }


    public function buscarTodas(): array
    {

        $categorias = [];
        $query = "SELECT id, nome, ind_boi, arrobas, aliases FROM " . Categoria::getNomeTabela() . ";";
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

    public function buscar(string $nome)
    {
        
        $tab = array();
        $q = "SELECT * FROM " . Categoria::getNomeTabela() . " WHERE JSON_CONTAINS (aliases, '\"" . $nome . "\"');";
        $set = $this->con->conn()->query($q);

        if ($set) {
            while ($reg = $set->fetch_assoc()) {
                $tab[] = $reg; 
            }
        }

        return $tab[0];

    }

}
<?php

namespace Src\Infraestrutura\Bd\Persistencia\Categoria;

use Src\Dominio\Categoria\Categoria;
use Src\Dominio\Categoria\CategoriaGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class CategoriaRepositorio implements CategoriaGateway{

    private Conexao $con ;

    public function __construct() {
        $this->con = new Conexao();
        
    }


    public function buscarTodas(): array {

        $query = "SELECT id, nome FROM " . Categoria::getNomeTabela() . ";"; // Ajuste para sua tabela
        $resultado = $this->con->conn()->query($query);

        if (!$resultado) {
            throw new \Exception("Erro ao buscar categorias: " . $this->con->conn()->error);
        }

        $categorias = [];
        $linha = $resultado->fetch_assoc();
        while ($linha) {
            $categorias[] = $linha;
        }

        return $categorias;
        }
    }
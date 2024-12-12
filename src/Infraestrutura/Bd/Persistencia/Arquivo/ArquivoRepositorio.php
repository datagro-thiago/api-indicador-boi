<?php

namespace Src\Infraestrutura\Bd\Persistencia\Arquivo;

use Ramsey\Uuid\Nonstandard\Uuid;
use Src\Dominio\Arquivo\Arquivo;
use Src\Dominio\Arquivo\ArquivoGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class ArquivoRepositorio implements ArquivoGateway{
    private Conexao $con;

    public function __construct() {
        $this->con = new Conexao();
    }

    public function salvar(Arquivo $arquivo): string {
        $id = Uuid::uuid4()->toString();
        $nome = $arquivo->getNome();
        $tipo = $arquivo->getTipo();
        $data = $arquivo->getData();
    
        // Preparando a consulta com parâmetros escapados
        $q = "INSERT INTO " . $arquivo->getTabela() . " (id, nome, tipo, data) VALUES (?, ?, ?, ?)";

        $insert = $this->con->conn()->execute_query( $q, [
            $id,
            $nome,
            $tipo,
            $data
        ] );
        if (!$insert) {
            return "";
        }
        return $id;
    }
}
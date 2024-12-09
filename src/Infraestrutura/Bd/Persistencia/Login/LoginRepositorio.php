<?php

namespace Src\Infraestrutura\Bd\Persistencia\Login;

use Src\Dominio\Login\Login;
use Src\Dominio\Login\LoginGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class LoginRepositorio implements LoginGateway {

    private Conexao $con ;

    public function __construct() {
        $this->con = new Conexao();
    }
    
    public function buscar(Login $login) : int {

        $q = "SELECT * FROM " . $login->getNomeTabela() ." WHERE nome_empresa = ?;";

        $result = $this->con->conn()->execute_query($q, [
            $login->getNomeEmpresa()
        ]);

        if( $result and $result->num_rows > 0) { 
            return 1;
        }
        $result->close();
        return 0;
    }
}
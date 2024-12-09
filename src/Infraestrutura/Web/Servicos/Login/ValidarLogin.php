<?php 
namespace Src\Infraestrutura\Web\Servicos\Login;

use Src\Dominio\Login\Login;
use Src\Infraestrutura\Bd\Persistencia\Login\LoginRepositorio;

class ValidarLogin {

    private LoginRepositorio $repositorio;
    public function __construct() {
        $this->repositorio = new LoginRepositorio();
    }

    public function validar(Login $login): array {
        $status = 1;
        $mensagem = "Email valido";

        $ok = $this->repositorio->buscar($login);
        if ($ok == 0) {
            $status = 0;
            $mensagem = "Email não encontrado.";
        }

        return [
            "status"=> $status,
            "mensagem"=> $mensagem,
        ];
    }
}
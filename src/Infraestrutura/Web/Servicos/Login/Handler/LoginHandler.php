<?php

namespace Src\Infraestrutura\Web\Servicos\Login\Handler;

use Src\Aplicacao\Login\Comando\ComandoValidar;
use Src\Dominio\Login\Login;
use Src\Infraestrutura\Web\Servicos\Login\ServicoLogin;
class LoginHandler {

    private ServicoLogin $ServicoLogin;

    public function __construct() {
        $this->ServicoLogin = new ServicoLogin();
    }

    public function handle(ComandoValidar $comando): array {
        $status = 1;
        $mensagem = "Sucesso ao validar login: ";

        $login = new Login(
            $comando->getNomeEmpresa(),
            $comando->getEmail(),
            $comando->getSenha(),
        );

        $ok = $this->ServicoLogin->validar($login);
        
        if ($ok["status"] == 0) { 
            $status = 0;
            $mensagem .= "Erro ao validar login: ". $ok["mensagem"];
        }

        return [
            "status"=> $status,
            "mensagem"=> $mensagem
        ];
    }
}
<?php

namespace Src\Infraestrutura\Web\Controladores;

use Src\Aplicacao\Login\Comando\ComandoValidar;
use Src\Infraestrutura\Web\Servicos\Login\Handler\LoginHandler;
use Symfony\Component\HttpFoundation\Request;

class ControladorLogin {

    private LoginHandler $handler;

    public function __construct() {
        $this->handler = new LoginHandler();
    }
    public function login(Request $request): array {

        // $nomeEmpresa =$request->get("nomeEmpresa");
        // $email =$request->get("email");
        // $senha =$request->get("senha");

        $nomeEmpresa = "Beto";
        $email ="teste";
        $senha ="teste";

        $comando = new ComandoValidar(
            $nomeEmpresa,
            $email,
            $senha
        );

        $handler = $this->handler->handle($comando);

        return [
            "status" =>  $handler["status"]
        ];

    }
}
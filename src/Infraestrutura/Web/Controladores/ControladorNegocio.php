<?php

namespace Src\Infraestrutura\Web\Controladores;

use Src\Aplicacao\Negocio\Comando\ComandoProcessar;
use Src\Infraestrutura\Web\Servicos\Login\ValidarLogin;
use Src\Infraestrutura\Web\Servicos\Negocio\Handler\ProcessarNegocioHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControladorNegocio
{
    private ProcessarNegocioHandler $processarNegocioHandler;

    public function __construct() {
        $this->processarNegocioHandler = new ProcessarNegocioHandler();
       
    }
    public function upload(Request $request): JsonResponse
    {        
        $retornoRequisicao = [
            "mensagem" => "Dados ausentes.",
            "statusCode" => 400,
        ];

        $login = new ControladorLogin();
        $validarLogin = $login->login($request);

        if($validarLogin["status"] == 0) {
            $retornoRequisicao["mensagem"][] = "Usuario sem permissão.";
            $retornoRequisicao["statusCode"][] = 403;

            return new JsonResponse($retornoRequisicao, $retornoRequisicao["statusCode"]);
        }
        
        $negocio = $request->files->get("arquivo");
        $remetente = $request->get("remetente");
        
        if ($negocio and $remetente) {
            $tipoNegocio = pathinfo($negocio->getClientOriginalName(), PATHINFO_EXTENSION);
            $tempName = $negocio->getRealPath();

            $comando = new ComandoProcessar(
                $tipoNegocio,
                $tempName,
                $remetente,
                $negocio,
                $negocio->getClientOriginalName(),
                $negocio->getFileName()
            );

            $handler = $this->processarNegocioHandler->handler($comando);

            if($handler["status"] == "erro") {
                $retornoRequisicao["mensagem"][]= $handler["mensagem"];
                $retornoRequisicao["statusCode"][] = 400;
            }
            
            $retornoRequisicao["mensagem"]= $handler["mensagem"];
            $retornoRequisicao["statusCode"] = 200;
            $retornoRequisicao["rastreio"] = $handler["rastreio"];
        }

        return new JsonResponse($retornoRequisicao, $retornoRequisicao["statusCode"]);

    }
}
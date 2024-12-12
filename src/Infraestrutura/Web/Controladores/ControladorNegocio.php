<?php

namespace Src\Infraestrutura\Web\Controladores;

use Src\Aplicacao\Negocio\Comando\ComandoBuscar;
use Src\Aplicacao\Negocio\Comando\ComandoProcessar;
use Src\Infraestrutura\Web\Servicos\Negocio\Handler\ProcessarNegocioHandler;
use Src\Infraestrutura\Web\Util\Auditoria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ControladorNegocio
{
    private ProcessarNegocioHandler $processarNegocioHandler;
    private string $ini0;
    private string $api_version;
    public function __construct() {
        $this->processarNegocioHandler = new ProcessarNegocioHandler();
        date_default_timezone_set ("Brazil/East");
	    $this->ini0 = microtime(true);
	    $this->api_version = "1.0.0";
       
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
            $retornoRequisicao["statusCode"] = 403;
            $retornoRequisicao["audit"] = Auditoria::audit($this->ini0, $this->api_version);

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

            $handler = $this->processarNegocioHandler->processarNegocio($comando);

            if($handler["status"] === 0) {
                $retornoRequisicao["mensagem"] = $handler["mensagem"];
                $retornoRequisicao["statusCode"] = 400;
            } else {
                $retornoRequisicao["mensagem"]= $handler["mensagem"];
                $retornoRequisicao["statusCode"] = 200;
                $retornoRequisicao["rastreio"] = $handler["rastreio"];
                
            }
            
            $retornoRequisicao["audit"] = Auditoria::audit($this->ini0, $this->api_version);
        }

        return new JsonResponse($retornoRequisicao, $retornoRequisicao["statusCode"]);

    }

    public function recuperarArquivo(Request $request):JsonResponse {
        $id = $request->get("id");
        $senha = $request->get("senha");

        $retorno = [
            "statusCode"=> 422,
            "mensagem" => "Obrigatorio ID do negocio e senha"
        ];

        if (!empty($id) && !empty($senha)) {
            $comando = new ComandoBuscar(
                $senha,
                $id,
            );

            $handler = $this->processarNegocioHandler->buscarNegocio($comando);
            if($handler["status"] === 0) {
                $retorno = [
                    "mensagem"=> $handler["mensagem"],
                    "statusCode" => 403
                ];
            } else {
                $retorno = [
                    "negocio" => $handler["negocio"],
                    "statusCode" => 200
                ];    
            }
        }

        return new JsonResponse ($retorno, $retorno["statusCode"]);
    }
}
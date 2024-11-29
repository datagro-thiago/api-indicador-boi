<?php

namespace Src\Input\Infraestrutura\Web\Controladores;
include $_SERVER['DOCUMENT_ROOT'] ."\input\src\aplicacao\arquivo\comando\ComandoProcessar.php";
include $_SERVER['DOCUMENT_ROOT'] ."\input\src\Infraestrutura\web\servicos\handler\ProcessarArquivoHandler.php";

use Src\Aplicacao\Arquivo\Comando\ComandoProcessar;
use Src\Infraestrutura\Web\Servicos\Handler\ProcessarArquivoHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControladorArquivo {
    
    public function upload(Request $request): Response {
        $arquivo = $request->files->get("arquivo");
       
        if ($arquivo) { 
            $tipoArquivo = pathinfo($arquivo->getClientOriginalName(), PATHINFO_EXTENSION);
            $comando = new ComandoProcessar($tipoArquivo, $arquivo);
            $handler = ProcessarArquivoHandler::handle($comando);
            return new Response($handler); 
        }

        return new Response("");
    }
}
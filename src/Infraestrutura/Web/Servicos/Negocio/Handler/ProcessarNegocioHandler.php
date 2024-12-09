<?php

namespace Src\Infraestrutura\Web\Servicos\Negocio\Handler;

define ('CAMINHO_LOGS', $_SERVER['DOCUMENT_ROOT'] . '/negocios-input/src/Infraestrutura/Logs');

use Src\Aplicacao\Arquivo\Arquivo;
use Src\Aplicacao\Negocio\Comando\ComandoProcessar;
use Src\Infraestrutura\Web\Servicos\Negocio\ProcessarNegocio;

//Manipular Negocios e diretorios
class ProcessarNegocioHandler{

    private ProcessarNegocio $processarNegocio;
    public function __construct(){
        $this->processarNegocio = new ProcessarNegocio();
    }

    public function handler(ComandoProcessar $comandoProcessar): array { 
        $job = date ("Y-m-d-H-i-s");
        $retorno = [];
        $status = 1;
        $mensagem = "Sucesso ao processar arquivo.";

        //salvar e recuperar endereco do Negocio
        $enderecoNegocio = Arquivo::salvarArquivoLocalmente(
            CAMINHO_LOGS,
            $comandoProcessar->getNome(),
            $comandoProcessar->getCaminhoNegocio(),
            $comandoProcessar->getRemetente()
        );
        
        if ($enderecoNegocio["status"] == 0) {
            $status = 0;
            $mensagem = $enderecoNegocio["mensagem"];

            return $retorno;
        }
        
        try {
            $processarNegocio = $this->processarNegocio->processar($enderecoNegocio["caminhoArquivo"], $comandoProcessar->getRemetente(), $job, CAMINHO_LOGS);

            if ($processarNegocio["status"] == 0) {
                $status  = 0;
                $mensagem = "Erro no processamento.";
            }

            return [
                "status"=> $status,
                "mensagem" => $mensagem,
                "rastreio" => $processarNegocio["rastreio"]
            ];
        } catch (\Exception $e) {
            $retorno["mensagem"] = "Erro inesperado: " . $e->getMessage();
            return $retorno;
        } 
    }
}
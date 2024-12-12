<?php

namespace Src\Infraestrutura\Web\Servicos\Negocio\Handler;

use Src\Aplicacao\Negocio\Comando\ComandoBuscar;
use Src\Infraestrutura\Web\Servicos\Arquivo\ServicoArquivo;

define('CAMINHO_LOGS', $_SERVER['DOCUMENT_ROOT'] . '/negocios-input/src/Infraestrutura/Logs');

use Src\Aplicacao\Negocio\Comando\ComandoProcessar;
use Src\Infraestrutura\Web\Servicos\Negocio\ProcessarNegocio;

//Manipular Negocios e diretorios
class ProcessarNegocioHandler
{

    private ProcessarNegocio $processarNegocio;
    public function __construct()
    {
        $this->processarNegocio = new ProcessarNegocio();
    }

    public function processarNegocio(ComandoProcessar $comandoProcessar): array
    {
        $job = date("Y-m-d-H-i-s");
        $status = 1;
        $mensagem = "Sucesso ao processar arquivo.";
        $retorno = [];

        //salvar e recuperar endereco do Negocio
        $lote = ServicoArquivo::salvarArquivoLocalmente(
            CAMINHO_LOGS,
            $comandoProcessar->getNome(),
            $comandoProcessar->getCaminhoNegocio(),
            $comandoProcessar->getRemetente(),
            $job
        );

        if ($lote["status"] == 0) {
            $status = 0;
            $mensagem = $lote["mensagem"];

            return $retorno;
        }

        try {
            $processarNegocio = $this->processarNegocio->processar(
                $lote["caminhoArquivo"],
                $comandoProcessar->getRemetente(),
                $job,
                CAMINHO_LOGS,
                $lote["idLote"],
            );

            if ($processarNegocio["status"] == 0) {
                $status = 0;
                $mensagem = $processarNegocio["mensagem"];
            }

            return [
                "status" => $status,
                "mensagem" => $mensagem,
                "rastreio" => $processarNegocio["rastreio"],
            ];

        } catch (\Exception $e) {
            $retorno["mensagem"] = "Erro inesperado: " . $e->getMessage();
            $retorno["status"] = 0;
            return $retorno;
        }

    }

    public function buscarNegocio(ComandoBuscar $comandoBuscar): array
    {
        $retorno = [];
        try {
            $negocio = $this->processarNegocio->buscarNegocio(
                $comandoBuscar->getId(),
                $comandoBuscar->getSenha()
            );

            if ($negocio["status"] === 0) {
                $retorno = [
                    "status" => 0,
                    "mensagem" => $negocio["mensagem"],
                ];

            } else {
                $retorno = [
                    "status" => 1,
                    "negocio" => $negocio["negocio"],
                ];

            }

            return $retorno;

        } catch (\Exception $e) {
            $retorno = [
                "status" => 0,
                "mensagem" => "Erro inesperado: " . $e->getMessage(),
            ];

            return $retorno;
        }
    }
}
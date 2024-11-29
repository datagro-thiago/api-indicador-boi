<?php

namespace Src\Infraestrutura\Web\Servicos\Handler;
include $_SERVER['DOCUMENT_ROOT'] ."\input\src\infraestrutura\web\servicos\ProcessarArquivo.php";


use Src\Aplicacao\Arquivo\Comando\ComandoProcessar;
use Src\Infraestrutura\Web\Servicos\ProcessarArquivo;

class ProcessarArquivoHandler{

    public static function handle(ComandoProcessar $comandoProcessar): string{ 
        $arquivo = ProcessarArquivo::processar($comandoProcessar->getCaminhoArquivo());
        try {
            ProcessarArquivo::salvarArquivo( $arquivo);
            return 'success';
        } catch (\Exception $e) {
            throw new \PDOException($e->getMessage());
        } 
    }

}
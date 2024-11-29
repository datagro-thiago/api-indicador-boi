<?php

namespace Src\Aplicacao\Arquivo\Comando;

class ComandoProcessar {
    
    private string $tipoArquivo;
    private string $caminhoArquivo;

    public function __construct(string $tipoArquivo, string $caminhoArquivo) {
        $this->tipoArquivo = $tipoArquivo;
        $this->caminhoArquivo = $caminhoArquivo;
    }

    public function getCaminhoArquivo(): string {
        return $this->caminhoArquivo;
    }

    public function getTipoArquivo(): string {
        return $this->tipoArquivo;
    }

}
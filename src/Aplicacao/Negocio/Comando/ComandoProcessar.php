<?php

namespace Src\Aplicacao\Negocio\Comando;

class ComandoProcessar {
    private string $nome;
    private string $tipoNegocio;
    private string $caminhoNegocio;
    private string $remetente;
    private string $negocio;
    private string $nomeNegocio;

    public function __construct(
        string $tipoNegocio,
        string $caminhoNegocio,
        string $remetente,
        string $negocio,
        string $nome,
        string $nomeNegocio,
        ) {
        $this->nomeNegocio = $nomeNegocio;
        $this->nome = $nome;
        $this->tipoNegocio = $tipoNegocio;
        $this->caminhoNegocio = $caminhoNegocio;
        $this->remetente = $remetente;
        $this->negocio = $negocio;
    }

    public function getNomeNegocio(): string {
        return $this->nomeNegocio;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getNegocio(): string {
        return $this->negocio;
    }

    public function getRemetente(): string {
        return $this->remetente;
    }

    public function getCaminhoNegocio(): string {
        return $this->caminhoNegocio;
    }

    public function getTipoNegocio(): string {
        return $this->tipoNegocio;
    }

}
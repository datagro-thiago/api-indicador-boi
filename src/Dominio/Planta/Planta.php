<?php

namespace Src\Dominio\Planta;

class Planta {

    private string $nome;

    public function __construct(
        string $nome
    )
    {
        $this->nome = $nome;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getNomeTabela(): string {
        $nomeTabela = "indicadordoboi.plantas";
        return $nomeTabela;
    }
}
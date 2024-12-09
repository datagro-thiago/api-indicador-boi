<?php

namespace Src\Dominio\Industria;


class Industria {

    private int $id;

    private string $nome;

    public function __construct(string $nome) {
        $this->nome = $nome;
    }

    public function setId(int $id ) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getNomeTabela(): string {
        $nomeTabela = "indicadordoboi.industrias";
        return $nomeTabela;
    }
}
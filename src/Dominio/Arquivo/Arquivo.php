<?php

namespace Src\Dominio\Arquivo;

class Arquivo {
    private string $nome;
    private string $tipo;
    private string $data;

    public function __construct(string $nome, string $tipo, string $data) {
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->data = $data;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getTipo(): string {
        return $this->tipo;
    }
    public function getData(): string {
        return $this->data;
    }

    public function getTabela(): string {
        $tabela = "indicadordoboi.arquivos";
        return $tabela;
    }
}
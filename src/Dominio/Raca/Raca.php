<?php

namespace Src\Dominio\Raca;

class Raca {
    private int $id;
    private string $nome;
    private string $aliases;

    public function __construct(
        int $id,
        string $nome,
        string $aliases
    ) {

        $this->id = $id;
        $this->nome = $nome;
        $this->aliases = $aliases;

    }

    public function getId(): int {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }
    public function getAliases(): string {
        return $this->aliases;
    }


}
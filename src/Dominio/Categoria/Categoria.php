<?php

namespace Src\Dominio\Categoria;

class Categoria
{
    private int $id;
    private string $nome;

    private int $indBoi;
    private int $arrobas;
    private string $aliases;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }
    public function setInd(int $ind)
    {
        $this->indBoi = $ind;
    }
    public function setAliases(string $aliases)
    {
        $this->aliases = $aliases;
    }
    public function setArrobas(int $arrobas)
    {
        $this->arrobas = $arrobas;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
    public function getIndBoi(): int
    {
        return $this->indBoi;
    }

    public function getAliases(): string
    {
        return $this->aliases;
    }

    public function getArrobas(): int
    {
        return $this->arrobas;
    }

    public static function getNomeTabela(): string
    {
        $nomeTabela = "indicadordoboi.categorias";
        return $nomeTabela;
    }
}

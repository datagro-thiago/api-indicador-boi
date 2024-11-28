<?php

namespace Src\Dominio;

class Arquivo {
    private \DateTime $dataHora;
    private string $idNegocio;
    private \DateTime $dtNegocio;
    private string $operacao;
    private string $idDisp;
    private string $aprovado;
    private string $dtAprov;
    private string $nome;
    private string $anonimo;
    private string $bonus;
    private float $vBonus;
    private string $raca;
    private string $categoria;
    private string $origem;
    private string $destino;
    private string $frete;
    private string $funrural;
    private string $nutricao;
    private float $diasPagto;
    private string $propriedade;
    private float $quantidade;
    private string $abate;
    private \DateTime $dtAbate;
    private string $abatedouro;
    private float $valor;
    private string $presomodo;
    private string $pesopercent;

    public function __construct(
        \DateTime $dataHora,
        string $idNegocio,
        \DateTime $dtNegocio,
        string $operacao,
        string $idDisp,
        string $aprovado,
        string $dtAprov,
        string $nome,
        string $anonimo,
        string $bonus,
        float $vBonus,
        string $raca,
        string $categoria,
        string $origem,
        string $destino,
        string $frete,
        string $funrural,
        string $nutricao,
        float $diasPagto,
        string $propriedade,
        float $quantidade,
        string $abate,
        \DateTime $dtAbate,
        string $abatedouro,
        float $valor,
        string $presomodo,
        string $pesopercent 
    ) {
        $this->dataHora = $dataHora;
        $this->idNegocio = $idNegocio;
        $this->dtNegocio = $dtNegocio;
        $this->operacao = $operacao;
        $this->idDisp = $idDisp;
        $this->aprovado = $aprovado;
        $this->dtAprov = $dtAprov;
        $this->nome = $nome;
        $this->anonimo = $anonimo;
        $this->bonus = $bonus;
        $this->vBonus = $vBonus;
        $this->raca = $raca;
        $this->categoria = $categoria;
        $this->origem = $origem;
        $this->destino = $destino;
        $this->frete = $frete;
        $this->funrural = $funrural;
        $this->nutricao = $nutricao;
        $this->diasPagto = $diasPagto;
        $this->propriedade = $propriedade;
        $this->quantidade = $quantidade;
        $this->abate = $abate;
        $this->dtAbate = $dtAbate;
        $this->abatedouro = $abatedouro;
        $this->valor = $valor;
        $this->presomodo = $presomodo;
        $this->pesopercent = $pesopercent; 
    }

    public static function novo(object $object): Arquivo
    {
        return new Arquivo(
            $object->dataHora ?? new \DateTime(), 
            $object->idNegocio ?? "",
            $object->dtNegocio ?? new \DateTime(),
            $object->operacao ?? "",
            $object->idDisp ?? "",
            $object->aprovado ?? "",
            $object->dtAprov ?? "",
            $object->nome ?? "",
            $object->anonimo ?? "",
            $object->bonus ?? "",
            $object->vBonus ?? 0.0,
            $object->raca ?? "",
            $object->categoria ?? "",
            $object->origem ?? "",
            $object->destino ?? "",
            $object->frete ?? "",
            $object->funrural ?? "",
            $object->nutricao ?? "",
            $object->diasPagto ?? 0.0,
            $object->propriedade ?? "",
            $object->quantidade ?? 0.0,
            $object->abate ?? "",
            $object->dtAbate ?? new \DateTime(),
            $object->abatedouro ?? "",
            $object->valor ?? 0.0,
            $object->presomodo ?? "",
            $object->pesopercent ?? ""
        );
    }

    public function getDataHora(): \DateTime
    {
        return $this->dataHora;
    }

    public function getIdNegocio(): string
    {
        return $this->idNegocio;
    }

    public function getDtNegocio(): \DateTime
    {
        return $this->dtNegocio;
    }

    public function getOperacao(): string
    {
        return $this->operacao;
    }

    public function getIdDisp(): string
    {
        return $this->idDisp;
    }

    public function getAprovado(): string
    {
        return $this->aprovado;
    }

    public function getDtAprov(): string
    {
        return $this->dtAprov;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getAnonimo(): string
    {
        return $this->anonimo;
    }

    public function getBonus(): string
    {
        return $this->bonus;
    }

    public function getVBonus(): float
    {
        return $this->vBonus;
    }

    public function getRaca(): string
    {
        return $this->raca;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function getOrigem(): string
    {
        return $this->origem;
    }

    public function getDestino(): string
    {
        return $this->destino;
    }

    public function getFrete(): string
    {
        return $this->frete;
    }

    public function getFunrural(): string
    {
        return $this->funrural;
    }

    public function getNutricao(): string
    {
        return $this->nutricao;
    }

    public function getDiasPagto(): float
    {
        return $this->diasPagto;
    }

    public function getPropriedade(): string
    {
        return $this->propriedade;
    }

    public function getQuantidade(): float
    {
        return $this->quantidade;
    }

    public function getAbate(): string
    {
        return $this->abate;
    }

    public function getDtAbate(): \DateTime
    {
        return $this->dtAbate;
    }

    public function getAbatedouro(): string
    {
        return $this->abatedouro;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function getPresomodo(): string
    {
        return $this->presomodo;
    }

    public function getPesoPercent(): string
    {
        return $this->pesopercent;
    }


}

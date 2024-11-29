<?php

namespace Src\Dominio;

use Src\Dominio\Enum\ModalidadeEnum;
use Src\Dominio\Enum\OperacaoEnum;

class Arquivo {
    private int $id;
    private \DateTime $dataRecebimento;
    private \DateTime $dataAprovacao;
    private int $aprovado;
    private string $agenteColaborador;
    private string $idNegocio;
    private \DateTime $dataNegocio;
    private \DateTime $dataAbate;
    private int $quantidade;
    private OperacaoEnum $operacao;
    private ModalidadeEnum $modalidade;
    private array $bonus;
    private float $vbonus;
    private int $categoria;
    private int $raca;
    private int $nutricao;
    private int $origem;
    private int $destino;
    private string $fazenda;
    private string $planta;
    private string $frete;
    private int $funrural;
    private int $diasPagto;
    private float $valorBase;
    private string $abatedouro;
    private string $pesomodo;
    private float $pesopercent;
    private \DateTime $inserido;
    private \DateTime $alterado;

    protected function __construct(
        \DateTime $dataRecebimento,
        \DateTime $dataAprovacao,
        int $aprovado,
        string $agenteColaborador, //ALTERAR 
        string $idNegocio,
        \DateTime $dataNegocio,
        \DateTime $dataAbate,
        int $quantidade,
        OperacaoEnum $operacao,
        ModalidadeEnum $modalidade,
        array $bonus,
        float $vbonus,
        int $categoria,
        int $raca,
        int $nutricao,
        int $origem,
        int $destino,
        string $fazenda,
        string $planta,
        string $frete,
        int $funrural,
        int $diasPagto,
        float $valorBase,
        string $abatedouro,
        string $pesomodo,
        float $pesopercent,

    ) {
        $this->id = 1;
        $this->dataRecebimento = $dataRecebimento;
        $this->dataAprovacao = $dataAprovacao;
        $this->aprovado = $aprovado;
        $this->agenteColaborador = $agenteColaborador;
        $this->idNegocio = $idNegocio;
        $this->dataNegocio = $dataNegocio;
        $this->dataAbate = $dataAbate;
        $this->quantidade = $quantidade;
        $this->operacao = $operacao;
        $this->modalidade = $modalidade;
        $this->bonus = $bonus;
        $this->vbonus = $vbonus;
        $this->categoria = $categoria;
        $this->raca = $raca;
        $this->nutricao = $nutricao;
        $this->origem = $origem;
        $this->destino = $destino;
        $this->fazenda = $fazenda;
        $this->planta = $planta;
        $this->frete = $frete;
        $this->funrural = $funrural;
        $this->diasPagto = $diasPagto;
        $this->valorBase = $valorBase;
        $this->abatedouro = $abatedouro;
        $this->pesomodo = $pesomodo;
        $this->pesopercent = $pesopercent;
        $this->inserido = new \DateTime();
        $this->alterado = new \DateTime(); 
    }

    public static function novo(
        \DateTime $dataRecebimento,
        \DateTime $dataAprovacao,
        int $aprovado,
        string $agenteColaborador,
        string $idNegocio,
        \DateTime $dataNegocio,
        \DateTime $dataAbate,
        int $quantidade,
        OperacaoEnum $operacao,
        ModalidadeEnum $modalidade,
        array $bonus,
        float $vbonus,
        int $categoria,
        int $raca,
        int $nutricao,
        int $origem,
        int $destino,
        string $fazenda,
        string $planta,
        string $frete,
        int $funrural,
        int $diasPagto,
        float $valorBase,
        string $abatedouro,
        string $pesomodo,
        float $pesopercent
    ): self {
        return new self(
            $dataRecebimento,
            $dataAprovacao,
            $aprovado,
            $agenteColaborador,
            $idNegocio,
            $dataNegocio,
            $dataAbate,
            $quantidade,
            $operacao,
            $modalidade,
            $bonus,
            $vbonus,
            $categoria,
            $raca,
            $nutricao,
            $origem,
            $destino,
            $fazenda,
            $planta,
            $frete,
            $funrural,
            $diasPagto,
            $valorBase,
            $abatedouro,
            $pesomodo,
            $pesopercent
        );
    }

    public function getDataRecebimento(): \DateTime
    {
        return $this->dataRecebimento;
    }
    public function getDataAbate(): \DateTime {
        return $this->dataAbate;
    }

    public function getQuantidade(): string {
        return $this->quantidade;
    }

    public function getFazenda(): string {
        return $this->fazenda;
    }

    public function getPlanta(): string {
        return $this->planta;
    }

    public function getIdNegocio(): string
    {
        return $this->idNegocio;
    }

    public function getDataNegocio(): \DateTime
    {
        return $this->dataNegocio;
    }

    public function getOperacao(): OperacaoEnum
    {
        return $this->operacao;
    }

    public function getModalidade(): ModalidadeEnum
    {
        return $this->modalidade;
    }
    public function getDataAprovacao(): \DateTime {
        return $this->dataAprovacao;
    }

    public function getAprovado(): int
    {
        return $this->aprovado;
    }

    public function getAgenteColaborador(): string
    {
        return $this->agenteColaborador;
    }
    public function getBonus(): array
    {
        return $this->bonus;
    }

    public function getVBonus(): float
    {
        return $this->vbonus;
    }

    public function getCategoria(): int
    {
        return $this->categoria;
    }

    public function getRaca(): int
    {
        return $this->raca;
    }

    public function getNutricao(): int
    {
        return $this->nutricao;
    }

    public function getOrigem(): int
    {
        return $this->origem;
    }

    public function getDestino(): int
    {
        return $this->destino;
    }

    public function getFrete(): string
    {
        return $this->frete;
    }

    public function getFunrural(): int
    {
        return $this->funrural;
    }

    public function getDiasPagto(): int
    {
        return $this->diasPagto;
    }

    public function getValorBase(): float
    {
        return $this->valorBase;
    }

    public function getAbatedouro(): string
    {
        return $this->abatedouro;
    }

    public function getPesomodo(): string
    {
        return $this->pesomodo;
    }

    public function getPesoPercent(): float
    {
        return $this->pesopercent;
    }
}

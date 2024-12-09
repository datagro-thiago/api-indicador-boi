<?php

namespace Src\Dominio\Negocio;

use DateTime;
use Src\Dominio\Utils\DataUtils;

class Negocio {
    private \DateTime $dataRecebimento;
    private ?\DateTime $dataAprovacao; // Pode ser NULL
    private ?int $aprovado; // Pode ser NULL
    private string $fonte; // ENUM('I', 'AC')
    private int $parte;
    private ?string $idNegocio; // Pode ser NULL
    private \DateTime $dataNegocio;
    private ?string $dataAbate; // Pode ser NULL
    private int $quantidade;
    private string $operacao; // Enum('V', 'C')
    private string $modalidade; // Enum('T', 'B')
    private ?string $bonus; // CHAR(200), pode ser NULL
    private int $categoria;
    private ?int $raca; // Pode ser NULL
    private ?int $nutricao; // Pode ser NULL
    private int $origem;
    private int $destino;
    private ?int $planta; // Pode ser NULL
    private string $frete; // ENUM('CIF', 'FOB')
    private ?string $funrural; // Pode ser NULL
    private int $diasPagto;
    private float $valorBase;
    private string $abatedouro;
    private ?string $pesomodo; // ENUM('V', 'M')
    private ?float $pesopercent; // Pode ser NULL
    private \DateTime $inserido;
    private \DateTime $alterado;

    protected function __construct(
        \DateTime $dataRecebimento,
        ?\DateTime $dataAprovacao,
        ?int $aprovado,
        string $fonte,
        string $parte,
        ?string $idNegocio,
        \DateTime $dataNegocio,
        ?string $dataAbate,
        int $quantidade,
        string $operacao,
        string $modalidade,
        ?string $bonus,
        int $categoria,
        ?int $raca,
        ?int $nutricao,
        int $origem,
        int $destino,
        ?int $planta,
        string $frete,
        ?string $funrural,
        int $diasPagto,
        float $valorBase,
        ?string $pesomodo,
        ?float $pesopercent
    ) {
        $this->dataRecebimento = $dataRecebimento;
        $this->dataAprovacao = $dataAprovacao;
        $this->aprovado = $aprovado;
        $this->fonte = $fonte;
        $this->parte = $parte;
        $this->idNegocio = $idNegocio;
        $this->dataNegocio = $dataNegocio;
        $this->dataAbate = $dataAbate;
        $this->quantidade = $quantidade;
        $this->operacao = $operacao;
        $this->modalidade = $modalidade;
        $this->bonus = $bonus;
        $this->categoria = $categoria;
        $this->raca = $raca;
        $this->nutricao = $nutricao;
        $this->origem = $origem;
        $this->destino = $destino;
        $this->planta = $planta;
        $this->frete = $frete;
        $this->funrural = $funrural;
        $this->diasPagto = $diasPagto;
        $this->valorBase = $valorBase;
        $this->pesomodo = $pesomodo;
        $this->pesopercent = $pesopercent;
        $this->inserido = new \DateTime();
        $this->alterado = new \DateTime();
    }

    public static function novo(
 
        ?\DateTime $dataRecebimento,
        ?\DateTime $dataAprovacao,
        ?int $aprovado,
        string $fonte,
        int $parte,
        ?string $idNegocio,
        \DateTime $dataNegocio,
        ?string $dataAbate,
        int $quantidade,
        string $operacao,
        string $modalidade,
        ?string $bonus,
        int $categoria,
        ?int $raca,
        ?int $nutricao,
        int $origem,
        int $destino,
        ?int $planta,
        string $frete,
        ?string $funrural,
        int $diasPagto,
        float $valorBase,
        ?string $pesomodo,
        ?float $pesopercent
    ): self {
        return new self(
            $dataRecebimento,
            $dataAprovacao,
            $aprovado,
            $fonte,
            $parte,
            $idNegocio,
            $dataNegocio,
            $dataAbate,
            $quantidade,
            $operacao,
            $modalidade,
            $bonus,
            $categoria,
            $raca,
            $nutricao,
            $origem,
            $destino,
            $planta,
            $frete,
            $funrural,
            $diasPagto,
            $valorBase,
            $pesomodo,
            $pesopercent
        );
    }


    public function getDataRecebimento(): string
    {
        return DataUtils::ConverterParaString($this->dataRecebimento);
    }

    public function getDataAprovacao(): ?string
    {
        if($this->dataAprovacao != null) {
            return DataUtils::ConverterParaString($this->dataAprovacao);
        }
        return null;

    }

    public function getAprovado(): ?int
    {
        return $this->aprovado;
    }

    public function getFonte(): string
    {
        return $this->fonte;
    }

    public function getParte(): int
    {
        return $this->parte;
    }

    public function getIdNegocio(): ?string
    {
        return $this->idNegocio;
    }

    public function getDataNegocio(): string
    {
        return DataUtils::ConverterParaString($this->dataNegocio);
    }

    public function getDataAbate(): ?string
    {
        return $this->dataAbate;

    }

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    public function getOperacao(): string
    {
        return $this->operacao;
    }

    public function getModalidade(): string
    {
        return $this->modalidade;
    }
    public function getBonus(): ?string
    {
        return $this->bonus;
    }
    public function getCategoria(): int
    {
        return $this->categoria;
    }

    public function getRaca(): ?int
    {
        return $this->raca;
    }

    public function getNutricao(): ?int
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

    public function getPlanta(): ?int
    {
        return $this->planta;
    }

    public function getFrete(): string
    {
        return $this->frete;
    }

    public function getFunrural(): ?string
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

    public function getPesomodo(): ?string
    {
        return $this->pesomodo;
    }

    public function getPesopercent(): ?float
    {
        return $this->pesopercent;
    }

    public function getInserido(): string
    {
        return DataUtils::ConverterParaString($this->inserido);
    }

    public function getAlterado(): string
    {
        return DataUtils::ConverterParaString($this->alterado);
    }

    public function getNomeTabela(): string {
        $nomeTabela = "indicadordoboi.negocios_t";
        
        return $nomeTabela;
    }
}
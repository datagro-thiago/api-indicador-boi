<?php 

namespace Src\Infraestrutura\Web\Dtos;

class ArquivoDto {
    public \DateTime $dataHora;
    public string $idNegocio;
    public string $dtNegocio;
    public string $operacao;
    public string $idDisp;
    public string $aprovado;
    public string $dtAprov;
    public string $nome;
    public string $anonimo;
    public string $bonus;
    public float $vBonus;
    public string $raca;
    public string $categoria;
    public string $origem;
    public string $destino;
    public string $frete;
    public string $funrural;
    public string $nutricao;
    public float $diasPagto;
    public string $propriedade;
    public float $quantidade;
    public string $abate;
    public \DateTime $dtAbate;
    public string $abatedouro;
    public float $valor;
    public string $presomodo;
    public string $pesopercent;

    public function __construct(){}

}
<?php

namespace Src\Infraestrutura\Bd\Persistencia;

use ArquivoRepositorio;
use Src\Dominio\Arquivo;

class ArquivoRepositorioImpl implements ArquivoRepositorio {

    private \mysqli $con;
    public function __construct( \mysqli $con) {
        $this->con = $con;
    }
    
    //TODO: alterar nome tabela se for o caso
    public function salvar(Arquivo $arquivo): void {

        $colunasString = "";
        $tabelas = json_decode(file_get_contents(include $_SERVER['DOCUMENT_ROOT'] . "/input/config/tabela.json"), true);
        foreach((array)$tabelas["colunas"] as $tabela) {
            $colunasString = $tabela . ",";
        }
         
        $q = "INSERT INTO indicadordoboi.negociosOf ($colunasString) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $stmt = $this->con->prepare($q);
        $stmt->execute([
            $arquivo->getDataRecebimento(),    // dataRecebimento
            $arquivo->getDataAprovacao(),      // dataAprovacao
            $arquivo->getAprovado(),           // aprovado (inteiro)
            $arquivo->getAgenteColaborador(),  // agenteColaborador
            $arquivo->getIdNegocio(),          // idNegocio
            $arquivo->getDataNegocio(),        // dataNegocio
            $arquivo->getDataAbate(),          // dataAbate
            $arquivo->getQuantidade(),         // quantidade (float)
            $arquivo->getOperacao(),           // operacao (string)
            $arquivo->getModalidade(),         // modalidade (string)
            $arquivo->getBonus(),              // bonus (array de strings)
            $arquivo->getVBonus(),             // vBonus (float)
            $arquivo->getCategoria(),          // categoria (inteiro)
            $arquivo->getRaca(),               // raca (inteiro)
            $arquivo->getNutricao(),           // nutricao (inteiro)
            $arquivo->getOrigem(),             // origem (inteiro)
            $arquivo->getDestino(),            // destino (inteiro)
            $arquivo->getFazenda(),            // fazenda (string)
            $arquivo->getPlanta(),             // planta (string)
            $arquivo->getFrete(),              // frete (string)
            $arquivo->getFunrural(),           // funrural (inteiro)
            $arquivo->getDiasPagto(),          // diasPagto (float)
            $arquivo->getValorBase(),          // valorBase (float)
            $arquivo->getAbatedouro(),         // abatedouro (string)
            $arquivo->getPesomodo(),           // pesomodo (string)
            $arquivo->getPesoPercent()         // pesopercent (string ou float dependendo do caso)
        ]);

        $stmt->close();
    } 

}
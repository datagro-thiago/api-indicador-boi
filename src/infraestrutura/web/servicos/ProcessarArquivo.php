<?php

namespace Src\Infraestrutura\Web\Servicos;

use ArquivoRepositorio;
use Src\Dominio\Arquivo;
use Src\Dominio\Enum\ModalidadeEnum;
use Src\Dominio\Enum\OperacaoEnum;

class ProcessarArquivo{
    public static function processar(string $caminhoArquivo): array
    {
        $dados = json_decode(file_get_contents( $caminhoArquivo), true);

        $itens = [];
        foreach ($dados as $value) {
            $itens[] = Arquivo::novo(
                new \DateTime($value["dataRecebimento"]),
                new \DateTime($value["dataAprovacao"]),
                (int) $value["aprovado"],
                $value["fonte"], //IMPLEMENTAR ENUM E VALIDAR
                $value["idNegocio"],
                new \DateTime($value["dataNegocio"]),
                new \DateTime($value["dataAbate"]),
                (int) $value["quantidade"],
                OperacaoEnum::isValid($value["operacao"]),
                ModalidadeEnum::isValid($value["modalidade"]),
                $value["bonus"],
                (float) $value["vBonus"],
                (int) $value["categoria"],
                (int) $value["raca"],
                (int) $value["nutricao"],
                (int) $value["origem"],
                (int) $value["destino"],
                $value["fazenda"],
                $value["planta"],
                $value["frete"],
                (int) $value["funrural"],
                (int) $value["diasPagto"],
                (float) $value["valorBase"],
                $value["abatedouro"],
                $value["pesomodo"],
                (float) $value["pesopercent"]
                
            );
        }

        return $itens;
    }

    public static function gerarArquivo() {
        
    }

    public static function salvarArquivo( array $arquivo): void {
        foreach ($arquivo as $valor) {
            ArquivoRepositorio::salvar( arquivo: $valor);
        }
    }
}
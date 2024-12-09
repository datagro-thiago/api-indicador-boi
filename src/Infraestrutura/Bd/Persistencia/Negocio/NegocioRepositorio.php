<?php

namespace Src\Infraestrutura\Bd\Persistencia\Negocio;

use Ramsey\Uuid\Nonstandard\Uuid;
use Src\Dominio\Negocio\Negocio;
use Src\Dominio\Negocio\Gateway\NegocioGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class NegocioRepositorio implements NegocioGateway {

    private Conexao $con;

    public function __construct() {
        $this->con = new Conexao();
    }

    public function salvar(array $negocios): array {
        $nomeTabela = $negocios[0]->getNomeTabela();
        $tabela = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/negocios-input/config/tabela_negocios.json"), true);
        $colunas = (array)$tabela["colunas"];
        $colunasString = implode(", ", $colunas);
        
        $q = "INSERT INTO " . $nomeTabela  . " (". $colunasString. ") " . "VALUES ";
        $ids = [];
        $parametros = [];   
    
        try {
                foreach ($negocios as $negocio) {
                    $id = Uuid::uuid4()->toString();
                    $ids [] = $id;
                    // Adiciona a linha de placeholders
                    $valores[] = "(" . implode(",", array_fill(0, count($colunas), "?")) . ")";
                    
                    // Adiciona os valores aos parâmetros
                    array_push($parametros, 
                        $id,
                        $negocio->getDataRecebimento(),
                        $negocio->getDataAprovacao(),
                        $negocio->getAprovado(),
                        $negocio->getFonte(),
                        $negocio->getParte(),
                        $negocio->getIdNegocio(),
                        $negocio->getDataNegocio(),
                        $negocio->getDataAbate(),
                        $negocio->getQuantidade(),
                        $negocio->getOperacao(),
                        $negocio->getModalidade(),
                        $negocio->getBonus(),
                        $negocio->getCategoria(),
                        $negocio->getRaca(),
                        $negocio->getNutricao(),
                        $negocio->getOrigem(),
                        $negocio->getDestino(),
                        $negocio->getPlanta(),
                        $negocio->getFrete(),
                        $negocio->getFunrural(),
                        $negocio->getDiasPagto(),
                        $negocio->getValorBase(),
                        $negocio->getPesomodo(),
                        $negocio->getPesoPercent(),
                        $negocio->getInserido(),
                        $negocio->getAlterado(),
                    );
                   
                }
            
                $q .= implode(", ", $valores);
                $stmt = $this->con->conn()->execute_query( $q, $parametros);

                if ($stmt === false) {
                    throw new \Exception("Erro ao preparar a query: " . $this->con->conn()->error);
                }   
            
            $this->con->conn()->close();
            $result = ["status" => 1, "id" => $ids];

            return $result;
        } catch (\Exception $e) {
            $this->con->conn()->close();
            echo "Erro ao executar a query: " . $e->getMessage();
            return [
                "status" => 0,
                "id" => $ids
            ];
        }
    }
    
    
}
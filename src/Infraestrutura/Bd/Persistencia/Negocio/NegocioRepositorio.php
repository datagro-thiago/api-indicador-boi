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
        $nomeTabela = Negocio::getNomeTabela();
        $tabela = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/negocios-input/config/tabela_negocios.json"), true);
        $colunas = (array)$tabela["colunas"];
        $colunasString = implode(", ", $colunas);
        
        $q = "INSERT INTO " . $nomeTabela  . " (". $colunasString. ") " . "VALUES ";
        $ids = [];
        $parametros = [];   
    
        try {
                foreach ($negocios as $negocio) {
                    $senha = uniqid();
                    $rastreio [] = [
                        "id_negocio" => $negocio->getIdnegocio(),
                        "selo" => $negocio->getId(),
                        "senha" => $senha,
                    ];
                    
                    // Adiciona a linha de placeholders
                    $valores[] = "(" . implode(",", array_fill(0, count($colunas), "?")) . ")";
                    
                    // Adiciona os valores aos parâmetros
                    array_push($parametros, 
                        $negocio->getId(),
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
                        $negocio->getNumeroDalinha(),
                        $negocio->getArquivo(),
                        $senha,
                    );
                }
            
                $q .= implode(", ", $valores);
                $stmt = $this->con->conn()->execute_query( $q, $parametros);

                if (!$stmt) {
                    throw new \Exception("Erro inesperado: " . $this->con->conn()->error);
                }   
            
            $this->con->conn()->close();
            $result = ["status" => 1, "id" => $rastreio];

            return $result;
        } catch (\Exception $e) {
            $this->con->conn()->close();
            echo "Erro ao executar a query: " . $e->getMessage();
            return [
                "status" => 0,
            ];
        }
    }

    
    public function buscar(string $id)  : array {
        $tabela = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/negocios-input/config/output_tabela_negocios.json"), true);
        $colunas = (array)$tabela["colunas"];
        $colunasString = implode(", ", $colunas);
        $nomeTabela = Negocio::getNomeTabela();

        $q = "SELECT " . $colunasString ." FROM " . $nomeTabela ." where id = ?";

        try {
            
            $stmt = $this->con->conn()->execute_query( $q, [$id] );
            if (!$stmt) {
                throw new \Exception("Erro inesperado: " . $this->con->conn()->error);
            } 
        
            $objeto = $stmt->fetch_object();
            
            if (!$objeto) {
                $result = ["status"=> 0,"mensagem" => "Objeto nao encontrado"];
                $this->con->conn()->close();
            } else {
                $this->con->conn()->close();
                $result = ["status" => 1, "negocio" => $objeto];
            }

            return $result;

        } catch (\Exception $e) {

            $this->con->conn()->close();
            echo "Erro ao executar a query: ". $e->getMessage();

            return [
                "status" => 0,
            ];
        }
    }

    public function buscarSenha(string $senha): bool {
        $nomeTabela = Negocio::getNomeTabela();

        $q = "SELECT * FROM " . $nomeTabela . " WHERE senha = ?;";
        try {
            $stmt = $this->con->conn()->execute_query($q, [$senha]);

            if ($stmt->num_rows === 0) {
                $this->con->conn()->close();
                return false;
            }
            
            $this->con->conn()->close();
            return true;
        } catch (\Exception $e) {
            $this->con->conn()->close();
            echo "". $e->getMessage();
            return false;
        }
    }
    
} 
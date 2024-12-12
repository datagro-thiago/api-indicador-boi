<?php


namespace Src\Infraestrutura\Bd\Persistencia\Industria;

use Exception;
use Src\Dominio\Industria\Industria;
use Src\Dominio\Industria\IndustriaGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class IndustriaRepositorio implements IndustriaGateway{

    private Conexao $con ;
    private Industria $industria;
    public function __construct() {
        $this->con = new Conexao();
    }

    public function buscar(Industria $industria): array  {

        $resultado = [];
        $itemParaPesquisa = "%" . $industria->getNome() . "%";

        $q = "SELECT * FROM " . $industria->getNomeTabela()  ." WHERE nome LIKE ?;";

        $result = $this->con->conn()->execute_query($q, [
            $itemParaPesquisa
        ]);
        // Obtenha os resultados
        $objeto = $result->fetch_object();
        $array = json_decode(json_encode($objeto), true);
        // Libera os recursos
        $result->close();
        $resultado = [
            "id" => $array["id"],
            "nome" => $array["nome"]
        ];
        return $resultado;
    }

    public function buscarIndustrias() {

        $q = "SELECT * FROM " . $this->industria->getNomeTabela() ."";
        $resultado = $this->con->conn()->query($q);
        if(!$resultado) {
            throw new Exception("Erro ao buscar industrias: ". $this->con->conn()->error);
        }

        $industrias = [];
        $linha = $resultado->fetch_assoc();
        while($linha) {
            $industrias[] = $linha;
            
        }

        return $industrias;
    }

    
}
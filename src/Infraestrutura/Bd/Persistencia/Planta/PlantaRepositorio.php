<?php 

namespace Src\Infraestrutura\Bd\Persistencia\Planta;

use Exception;
use Src\Dominio\Planta\Planta;
use Src\Dominio\Planta\PlantaGateway;
use Src\Infraestrutura\Bd\Conexao\Conexao;

class PlantaRepositorio implements PlantaGateway {
    private Conexao $con ;

    public function __construct() {
        $this->con = new Conexao();
    }

    
    public function salvar(Planta $planta):int
    {
        $q = "INSERT INTO " . $planta->getNomeTabela() . " (nome) VALUES (?)";

        try {
            
            $this->con->conn()->execute_query($q, [
                $planta->getNome()
            ]);

            $id =  $this->con->conn()->insert_id;

            return $id;
        }catch (\Exception $e){
            $erro = $e->getMessage();
        }
    }

    public function buscar(Planta $planta) : int {
        $q = "SELECT * FROM ". $planta->getNomeTabela() . "WHERE nome = ?";
        try {
            $buscar = $this->con->conn()->execute_query($q, [
                $planta->getNome()
            ]);

            $id = $buscar->fetch_assoc();
            return $id["id"];
        } catch(Exception $e) {
            $erro = $e->getMessage();
            return 0;
        }

    }

}
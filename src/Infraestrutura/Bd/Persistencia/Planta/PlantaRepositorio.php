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
        $aliasesJson = json_encode($planta->getAliases());
        $q = "INSERT INTO " . $planta->getNomeTabela() . " (nome, aliases) VALUES (?, ?)";

        try {
            $conn = $this->con->conn(); 

            if ($conn->execute_query($q, [ $planta->getNome(), $aliasesJson]) === TRUE) { 
                $id = $conn->insert_id; 
                return $id;
            } else {
                echo "Erro ao salvar: " . $conn->error; 
                return 0;
            }
            
        } catch (Exception $e) {
            echo 'Erro inesperado: ' . $e->getMessage();
            return 0;
        }

    }

    public function buscar(Planta $planta) : int {
        $nome = "%" . $planta->getNome() ."%";
        $q = "SELECT * FROM ". $planta->getNomeTabela() . "ALIKE nome ?";
        try {
            $buscar = $this->con->conn()->execute_query($q, [
                $nome
            ]);

            $id = $buscar->fetch_assoc();
            return $id["id"];
        } catch(Exception $e) {
            $erro = $e->getMessage();
            return 0;
        }

    }

    public function buscarTodas(): array
    {

        $query = "SELECT id, nome, aliases FROM " . Planta::getNomeTabela() . ";";
        $resultado = $this->con->conn()->query($query);

        if (!$resultado) {
            throw new \Exception("Erro ao buscar plantas: " . $this->con->conn()->error);
        }

        $plantas = [];
        if ($resultado) {
            while ($reg = $resultado->fetch_assoc()) {
                $plantas[] = $reg;  
            }
        }

        return $plantas;
    }

}
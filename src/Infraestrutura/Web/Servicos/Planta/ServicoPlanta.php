<?php

namespace Src\Infraestrutura\Web\Servicos\Planta;

use Src\Dominio\Planta\Planta;
use Src\Infraestrutura\Bd\Persistencia\Planta\PlantaRepositorio;

class ServicoPlanta {
    
    public static function criar(string $nome) {
        $repositorio = new PlantaRepositorio();
        $planta = new Planta (
            $nome,
        );
        $repositorio->salvar($planta);

    }

    public static function buscarPlanta(string $nomePlanta): int {

        
        $repositorio = new PlantaRepositorio();
        $planta = new Planta($nomePlanta);
        $id = $repositorio->buscar($planta);
        
        if($id != 0 ){
            return $id["id"];
        }
        
        $novaPlanta = $repositorio->salvar($planta);

        return $novaPlanta;
    }
}
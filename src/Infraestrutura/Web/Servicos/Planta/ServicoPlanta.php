<?php

namespace Src\Infraestrutura\Web\Servicos\Planta;

use Src\Dominio\Planta\Planta;
use Src\Infraestrutura\Bd\Persistencia\Planta\PlantaRepositorio;

class ServicoPlanta {
    
    public static function criar(string $nome): int {
        $repositorio = new PlantaRepositorio();
        $nomeFormatado = Planta::formatarNome($nome);
        $alias = [
            $nomeFormatado
        ];
        $planta = Planta::novo (
            $nome,
            $alias
        );
        $ok = $repositorio->salvar($planta);
        if (!empty($ok)) {
            return $ok;
        }

        return 0;
    }

    
    public static function carregarPlantas(): bool
    {
        if (empty(self::$plantasCache)) {
            $repositorio = new PlantaRepositorio();
            $plantas = $repositorio->buscarTodas();
            foreach ($plantas as $planta) {
                $planta['aliases'] = json_decode($planta['aliases'], true);
                $plantaObjeto = new Planta (
                   (int) $planta["id"],
                    $planta["nome"],
                    $planta["aliases"],
                );

                Planta::plantaCache($plantaObjeto);
            }
        }
        return true;
    }

    // public static function buscarPlantaNaBase(?string $nomePlanta): int {

    //     $repositorio = new PlantaRepositorio();
    //     $planta = new Planta($nomePlanta);
    //     $id = $repositorio->buscar($planta);
        
    //     if($id != 0 ){
    //         return $id;
    //     }
        
    //     $novaPlanta = $repositorio->salvar($planta);

    //     return $novaPlanta;
    // }

    public static function buscarPlanta(string $nome): int
    {
        $id = 0;
        $formatarNome = Planta::formatarNome($nome);
        $planta = Planta::buscarPlantaPorAlias($formatarNome);
        if (empty($planta)) {
            $id = self::criar($nome);
        }
        if (!empty($planta)) {
            $id = $planta->getId();
        }
        
        return $id ;
    }
}
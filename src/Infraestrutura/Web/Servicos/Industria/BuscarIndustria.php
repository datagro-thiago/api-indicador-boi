<?php

namespace Src\Infraestrutura\Web\Servicos\Industria;

use Src\Dominio\Industria\Industria;
use Src\Infraestrutura\Bd\Persistencia\Industria\IndustriaRepositorio;

class BuscarIndustria {

    private static array $industriasCache = [];

    public function carregarIndustrias() {
        if(empty($industriasCache)){
            $repositorio = new IndustriaRepositorio();
            self::$industriasCache = $repositorio->buscarIndustrias();
        }
    }

    public static function buscarIndustria(string $nomeIndustria): int {
        $repositorio = new IndustriaRepositorio();
        $industria = new Industria($nomeIndustria);
        $id = $repositorio->buscar($industria);

        if(!is_null($id)){
            return $id["id"];
        }
        $id = 0;

        return $id;
    }

    public static function buscarCategoria(string $nomeIndustria): int
    {
        var_dump($nomeIndustria);
        foreach (self::$industriasCache as $industria) {
            if ($industria["nome"] === $nomeIndustria) {
                
                return (int)$industria['id'];
            }
        }
        return 0;
    }
}
<?php

namespace Src\Infraestrutura\Web\Servicos\Raca;

use Src\Dominio\Raca\Raca;
use Src\Infraestrutura\Bd\Persistencia\Raca\RacaRepositorio;

class ServicoRaca {

    public static function carregarRacas(): bool
    {
        $repositorio = new RacaRepositorio();
        $racas = $repositorio->buscarTodas();
        foreach ($racas as $raca) {
            $raca['aliases'] = json_decode($raca['aliases'], true);
            $racaObjeto = new Raca(
                (int) $raca["id"],
                $raca["nome"],
                $raca["aliases"],
            );

            Raca::racaCache($racaObjeto);
        }

        return true;
    }

    public static function buscarRacaNaBase(string $nomeRaca): int {
        $repositorio = new RacaRepositorio();
        $alias = Raca::transformarEmAlias($nomeRaca);
        $raca = $repositorio->buscar($alias);
        $id = $raca["id"];

        return (int) $id;
    }


    public static function buscarRaca(string $alias): int
    {
        $id = 0;
        $aliasFormatado = Raca::transformarEmAlias($alias);

        $raca = Raca::buscarRacaPorAlias($aliasFormatado);
        if (!empty($raca)) {
            $id = $raca->getId();
        }
        
        return $id ;
    }
}
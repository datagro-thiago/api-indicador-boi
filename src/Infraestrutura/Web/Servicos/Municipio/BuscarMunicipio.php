<?php

namespace Src\Infraestrutura\Web\Servicos\Municipio;

use Src\Dominio\Municipio\Municipio;

class BuscarMunicipio {

    public static function buscarMunicipio(string $nomeMunicipio): int | null  {
        $formatarNome = Municipio::formatarNome($nomeMunicipio);

        $resultado = Municipio::buscarPorSlug($formatarNome);

        if(is_null($resultado)) {
            $nome = Municipio::formatarNomeSemEstado($nomeMunicipio);
            $resultado = Municipio::buscarPorNome($nome);
            if(is_null($resultado)) {
                return 0;
            }
          return $resultado;
        }

        return $resultado->getId();
    }
}
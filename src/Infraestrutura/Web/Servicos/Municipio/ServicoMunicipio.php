<?php

namespace Src\Infraestrutura\Web\Servicos\Municipio;

use Src\Dominio\Municipio\Municipio;

class ServicoMunicipio {

    public static function carregarMunicipios(): bool {
        $carregar = Municipio::carregarMunicipiosDaApi();
        return $carregar;
    }

    public static function buscarMunicipio(string $nomeMunicipio): int   {
        $formatarNomeSemEstado = Municipio::formatarNomeSemEstado($nomeMunicipio);
        $resultado = Municipio::buscarPorSlug($formatarNomeSemEstado);
        if(is_null($resultado)) {
            $nome = Municipio::formatarNomeSemEstado($nomeMunicipio);
            $resultado = Municipio::buscarPorNome($nome);
            if(is_null($resultado)) {
                return 0;
            }
          return (int) $resultado->getId();
        }

        return (int) $resultado->getId();
    }
}
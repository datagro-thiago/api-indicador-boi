<?php

namespace Src\Dominio\Municipio;

use Src\Dominio\Municipio\Municipio;

interface MunicipioGateway {
    public function buscar(Municipio $nome);
}
<?php

namespace Src\Dominio\Negocio\Gateway;

use Src\Dominio\Negocio\Negocio;

interface NegocioGateway {
    public function salvar(array $negocios);
}
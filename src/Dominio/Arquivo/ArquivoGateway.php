<?php

namespace Src\Dominio\Arquivo;

interface ArquivoGateway {
    public function salvar(Arquivo $arquivo): string;
}
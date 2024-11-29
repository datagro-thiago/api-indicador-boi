<?php

use Src\Dominio\Arquivo;

interface ArquivoRepositorio {
    public static function salvar(Arquivo $arquivo);
}
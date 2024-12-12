<?php

namespace Src\Dominio\Categoria;

interface CategoriaGateway {

    public function buscarTodas();
    public function buscar(string $alias);
}
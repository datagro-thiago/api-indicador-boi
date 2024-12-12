<?php

namespace Src\Infraestrutura\Web\Servicos\Categoria;

use Src\Dominio\Categoria\Categoria;
use Src\Infraestrutura\Bd\Persistencia\Categoria\CategoriaRepositorio;

class ServicoCategoria {
    
    

    public static function carregarCategorias(): bool
    {
        if (empty(self::$categoriasCache)) {
            $repositorio = new CategoriaRepositorio();
            $categorias = $repositorio->buscarTodas();
            foreach ($categorias as $categoria) {
                $categoria['aliases'] = json_decode($categoria['aliases'], true);
                $categoriaObjeto = new Categoria (
                   (int) $categoria["id"],
                    $categoria["nome"],
                    $categoria["ind_boi"],
                    $categoria["arrobas"],
                    $categoria["aliases"],
                );

                Categoria::categoriaCache($categoriaObjeto);
            }
        }
        return true;
    }

    public static function buscarCategoriaNaBase(string $nomeCategoria): int {
        $repositorio = new CategoriaRepositorio();
        $alias = Categoria::transformarEmAlias($nomeCategoria);
        $categoria = $repositorio->buscar($alias);
        $id = $categoria["id"];

        return (int) $id;
    }


    public static function buscarCategoria(string $alias): int
    {
        $id = 0;
        $aliasFormatado = Categoria::transformarEmAlias($alias);

        $categoria = Categoria::buscarCategoriaPorAlias($aliasFormatado);
        if (!empty($categoria)) {
            $id = $categoria->getId();
        }
        return $id ;
    }
}
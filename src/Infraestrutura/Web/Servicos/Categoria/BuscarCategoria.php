<?php

namespace Src\Infraestrutura\Web\Servicos\Categoria;

use Src\Infraestrutura\Bd\Persistencia\Categoria\CategoriaRepositorio;

class BuscarCategoria {
    
    private static array $categoriasCache = []; // Cache estático de categorias

    public static function carregarCategorias(): void
    {
        if (empty(self::$categoriasCache)) {
            $repositorio = new CategoriaRepositorio();
            self::$categoriasCache = $repositorio->buscarTodas(); // Buscar todas as categorias
        }
    }


    public static function buscarCategoria(string $nomeCategoria): int
    {
        var_dump($nomeCategoria);
        foreach (self::$categoriasCache as $categoria) {
            if ($categoria["nome"] === $nomeCategoria) {
                return (int)$categoria['id'];
            }
        }
        return 0;
    }
}
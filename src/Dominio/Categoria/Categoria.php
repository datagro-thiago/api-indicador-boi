<?php

namespace Src\Dominio\Categoria;

class Categoria
{
    private int $id;
    private string $nome;
    private array $aliases;
    private int $indBoi;
    private int $arrobas;

    private static array $categoriasCache = []; // Cache estático de categorias

    public function __construct(int $id, string $nome, int $indBoi, int $arrobas, array $aliases)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->indBoi = $indBoi;
        $this->arrobas = $arrobas;
        $this->aliases = $aliases;
    }

    public static function categoriaCache(Categoria $categoria) {
        self::$categoriasCache[] = $categoria;
    }

    public static function buscarCategoriaPorAlias(string $alias): Categoria | null{
        foreach (self::$categoriasCache as $categoria) {
            if (in_array($alias,$categoria->getAliases())) {
                return $categoria;
            }
        }
        return null;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getIndBoi() :int{
        return $this->indBoi;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }

    public function getArrobas(): int {
        return $this->arrobas;
    }


    public static function getNomeTabela(): string
    {
        $nomeTabela = "indicadordoboi.categorias c";
        return $nomeTabela;
    }

    public static function transformarEmAlias(string $nome): string {
        $aliases = [
            'Boi Castrado' => 'castrado',
            'Boi Inteiro' => 'inteiro',
            'Boi Magro' => 'magro',
            'Boi Marruco' => 'marruco',
            'Novilha 15@ acima' => 'novilhaacima',
            'Novilha ate 15@' => 'novilhadeate',
            'Vaca 15@ acima' => 'vacaacima',
            'Vaca de ate 15@' => 'vacadeate'
        ];
        $retorno = "";
        //Nao achou pela chave?
        if (key_exists($nome, $aliases)) {
            $retorno = $aliases[$nome];
        }
        //Procuro pelo valor
        if (in_array(strtolower($nome), $aliases)) {
            $retorno = strtolower($nome);
        }
        return $retorno;
    }
}

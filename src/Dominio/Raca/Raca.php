<?php

namespace Src\Dominio\Raca;

class Raca {
    private int $id;
    private string $nome;
    private array $aliases;

    private static array $racasCache = [];

    public function __construct(
        int $id,
        string $nome,
        array $aliases
    ) {

        $this->id = $id;
        $this->nome = $nome;
        $this->aliases = $aliases;

    }

    public static function racaCache(Raca $raca) {
        self::$racasCache[] = $raca;
    }

    public static function buscarRacaPorAlias(string $alias): Raca | null {
        
        if (!empty($alias)){
            
            foreach (self::$racasCache as $raca) {
                if (in_array ($alias, $raca->getAliases()) ) {
                    return $raca;
                }
                
            }
        }
        
        return null;
    }
    public function getId(): int {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }
    public function getAliases(): array {
        return $this->aliases;
    }

 
    public static function getNomeTabela(): string
    {
        $nomeTabela = "indicadordoboi.racas r";
        return $nomeTabela;
    }

    public static function transformarEmAlias(string $nome): string {
        $aliases = [
            'Angus' => 'angus',
            'Cruzamento Industrial' => 'cruzamentoindustrial',
            'Cruzamento Leiteiro' => 'cruzamentoleiteiro',
            'Duas ou mais raças' => 'duasoumaisracas',
            'Nelore' => "neloreanelorado",
            'Anelorado' => 'anelorado',
            'Raças Britânicas' => ["racabritanica", "britanicas", "britanica"],
            'Outros' => 'outros'
        ];
        $retorno = "";

        if (key_exists($nome, $aliases)) {
            $retorno = $aliases[$nome];
        }
        return $retorno;

    }

}
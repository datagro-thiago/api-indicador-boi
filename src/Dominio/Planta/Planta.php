<?php

namespace Src\Dominio\Planta;

class Planta {

    private int $id;
    private ?string  $nome;
    private array $aliases;
    private static array $plantaCache = [];

    public function __construct(
        int $id,
        ?string $nome,
        array $aliases
    )
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->aliases = $aliases;
    }

    public static function novo(
        string $nome,
        array $aliases,
    ) {
        return new Planta (
            0,
            $nome,
            $aliases
        );
    }

    public function getNome(): ?string {
        return $this->nome;
    }
    public function getId(): int {
        return $this->id;
    }

    public static function plantaCache(Planta $planta) {
        self::$plantaCache[] = $planta;
    }

    public function getAliases(): array {
        return $this->aliases;
    }

    public static function getNomeTabela(): string {
        $nomeTabela = "indicadordoboi.plantas";
        return $nomeTabela;
    }

    public function setAliases(string $alias, string $valor):void {
        $this->aliases[$alias] = $valor;
    }

    public static function transformarEmAlias(string $nome): string {

        $aliases = [
            self::getNome() => self::getAliases()
        ];
        $retorno = "";

        if (key_exists($nome, $aliases)) {
            $retorno = $aliases[$nome];
        }
        return $retorno;
    }

    public static function formatarNome(string $nome): string {
        // Remove tudo após o traço, converte para minúsculas e remove os espaços
        $antesTraco = explode(' - ', $nome)[0]; // Pega tudo antes do traço
        $nomeFormatado = str_replace([' ', '-'], '', $antesTraco); // Remove espaços e traços
        return strtolower($nomeFormatado); // Retorna em minúsculas
    }


    public static function buscarPlantaPorAlias(string $alias): Planta | null {
        
        if (!empty($alias)){
            
            foreach (self::$plantaCache as $planta) {
                if (in_array ($alias, $planta->getAliases()) ) {
                    return $planta;
                }
                
            }
        }
        
        return null;
    }
}
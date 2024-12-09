<?php 
namespace Src\Dominio\Negocio\Enum;

class PesoModoEnum {
    const V = "V";
    const M = "M";

    private string $value;
    
    public function __construct(string $value) {
        // O valor passado no construtor deve ser validado para garantir que é um valor válido
        if (!in_array($value, [self::V, self::M])) {
            throw new \InvalidArgumentException("Valor inválido para PesoModoEnum");
        }
        $this->value = $value;
    }

    public static function isValid($value): ?string {
        // Verifica se o valor é válido e retorna uma instância de PesoModoEnum ou null
        if ($value === self::V || $value === self::M) {
            return $value;
        }
        return null; // Se o valor não for válido, retorna null
    }

    public function getValor(): string {
        return $this->value;
    }
}

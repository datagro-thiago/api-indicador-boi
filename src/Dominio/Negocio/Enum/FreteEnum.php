<?php 
namespace Src\Dominio\Negocio\Enum;

class FreteEnum {
    const CIF = "CIF";
    const FOB = "FOB";

    private string $value;
    
    public function __construct(string $value) {
        // O valor passado no construtor deve ser validado para garantir que é um valor válido
        if (!in_array($value, [self::CIF, self::FOB])) {
            throw new \InvalidArgumentException("Valor inválido para FreteEnum");
        }
        $this->value = $value;
    }

    public static function isValid($value): ?string {
        // Verifica se o valor é válido e retorna uma instância de FreteEnum ou null
        if ($value === self::CIF || $value === self::FOB) {
            return $value;
        }
        return ""; // Se o valor não for válido, retorna null
    }

    public function getValor(): string {
        return $this->value;
    }
}

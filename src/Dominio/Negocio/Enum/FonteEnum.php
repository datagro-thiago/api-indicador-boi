<?php 
namespace Src\Dominio\Negocio\Enum;

class FonteEnum {
    const I = "I";
    const AC = "AC";

    private string $value;
    
    public function __construct(string $value) {
        // O valor passado no construtor deve ser validado para garantir que é um valor válido
        if (!in_array($value, [self::I, self::AC])) {
            throw new \InvalidArgumentException("Valor inválido para FonteEnum");
        }
        $this->value = $value;
    }

    public static function isValid($value): ?string {
        // Verifica se o valor é válido e retorna uma instância de FonteEnum ou null
        if ($value === self::I || $value === self::AC) {
            return $value;
        }
        return null; 
    }

}

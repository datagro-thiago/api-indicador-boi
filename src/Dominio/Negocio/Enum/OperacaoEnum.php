<?php 
namespace Src\Dominio\Negocio\Enum;

class OperacaoEnum {
    const V = "V";
    const C = "C";

    private string $value;
    
    public function __construct(string $value) {
        // O valor passado no construtor deve ser validado para garantir que é um valor válido
        if (!in_array($value, [self::V, self::C])) {
            throw new \InvalidArgumentException("Valor inválido para OperacaoEnum");
        }
        $this->value = $value;
    }

    public static function isValid($value): ?string {
        // Verifica se o valor é válido e retorna uma instância de OperacaoEnum ou null
        if ($value === self::V || $value === self::C) {
            return $value;
        }
        return ""; // Se o valor não for válido, retorna null
    }

    public function getValor(): string {
        return $this->value;
    }
}

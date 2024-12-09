<?php 
namespace Src\Dominio\Negocio\Enum;

class ModalidadeEnum {
    const T = "T";
    const B = "B";

    private string $value;
    
    public function __construct(string $value) {
        // O valor passado no construtor deve ser validado para garantir que é um valor válido
        if (!in_array($value, [self::T, self::B])) {
            throw new \InvalidArgumentException("Valor inválido para ModalidadeEnum");
        }
        $this->value = $value;
    }

    public static function isValid($value): ?string {
        // Verifica se o valor é válido e retorna uma instância de ModalidadeEnum ou null
        if ($value === self::T || $value === self::B) {
            return $value;
        }
        return ""; // Se o valor não for válido, retorna null
    }

    public function getValor(): string {
        return $this->value;
    }
}

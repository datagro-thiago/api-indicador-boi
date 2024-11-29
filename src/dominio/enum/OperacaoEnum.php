<?php

namespace Src\Dominio\Enum;
class OperacaoEnum {
    const V = "V";
    const C = "C";
    
    public function __construct(string $value) {
        $this->value = self::V;
        $this->type = self::C;
    }
    public static function isValid($value): OperacaoEnum | null {
        if(in_array($value, [self::V])) {
            return new OperacaoEnum(self::V);
        } elseif(in_array($value, [self::C])) {
            return new OperacaoEnum(self::C);
        }

        return null;

     }
 }
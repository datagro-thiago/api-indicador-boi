<?php 
namespace Src\Dominio\Enum;

class ModalidadeEnum {
    const T = "T";
    const B = "B";
    
    public function __construct(string $value) {
        $this->value = self::T;
        $this->type = self::B;
    }
    public static function isValid($value): ModalidadeEnum | null {
        if(in_array($value, [self::T])) {
            return new ModalidadeEnum(self::T);
        } elseif(in_array($value, [self::B])) {
            return new ModalidadeEnum(self::B);
        }

        return null;

     }
 }
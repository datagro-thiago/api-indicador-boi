<?php

namespace Src\Dominio\Negocio;

use Ramsey\Uuid\Nonstandard\Uuid;

class NegocioId {
    private string $id;
    public function getId(): string {
        return $this->id;
    }

    public static function unique() : string {
        $id = Uuid::uuid4()->toString();
        return $id;
    }
}
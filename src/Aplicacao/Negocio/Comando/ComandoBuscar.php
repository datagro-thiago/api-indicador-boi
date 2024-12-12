<?php

namespace Src\Aplicacao\Negocio\Comando;

class ComandoBuscar {
    private string $id;
    private string $senha;

    public function __construct(
        string $senha,
        string $id
        ) {
        $this->id = $id;
        $this->senha = $senha;;
    }

   public function getId(): string {
        return $this->id;
   }
   public function getSenha(): string {
        return $this->senha;
   }

}
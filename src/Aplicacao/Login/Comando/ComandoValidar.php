<?php

namespace Src\Aplicacao\Login\Comando;

class ComandoValidar {

    private string $nomeEmpresa;
    private string $email;
    private string $senha;
    

    public function __construct(
        string $nomeEmpresa,
        string $email,
        string $senha
    ){
        $this->nomeEmpresa = $nomeEmpresa;
        $this->email = $email;
        $this->senha = $senha;
    }

    public function getNomeEmpresa(): string {
        return $this->nomeEmpresa;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function getSenha(): string {
        return $this->senha;
    }

}
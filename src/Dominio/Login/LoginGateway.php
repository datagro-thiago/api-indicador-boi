<?php

namespace Src\Dominio\Login;

interface LoginGateway {
    
    public function buscar(Login $login);
    
}
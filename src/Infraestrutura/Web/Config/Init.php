<?php

namespace Src\Infraestrutura\Web\Config;

use Src\Infraestrutura\Web\Servicos\Categoria\ServicoCategoria;
use Src\Infraestrutura\Web\Servicos\Municipio\Handler\MunicipioHandler;
use Src\Infraestrutura\Web\Servicos\Planta\ServicoPlanta;
use Src\Infraestrutura\Web\Servicos\Raca\ServicoRaca;

class Init {

    public function __construct() {
        $this->buscarCategorias();
        $this->buscarMunicipios();
        $this->buscarRacas();
        $this->buscarPlantas();
    }

    public function buscarCategorias() {
        return ServicoCategoria::carregarCategorias();
    }

    public function buscarMunicipios() {
       return MunicipioHandler::carregarMunicipios();
    }
    
    public function buscarRacas() {
        return ServicoRaca::carregarRacas();
    }

    public function buscarPlantas() {
        return ServicoPlanta::carregarPlantas();
    }
}
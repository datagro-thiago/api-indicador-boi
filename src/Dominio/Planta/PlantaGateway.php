<?php

namespace Src\Dominio\Planta;

interface PlantaGateway {

    public function salvar(Planta $planta);

    public function buscar(Planta $planta);
}
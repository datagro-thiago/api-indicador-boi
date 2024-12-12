<?php
namespace Src\Infraestrutura\Web\Servicos\Municipio\Handler;

use Src\Infraestrutura\Web\Servicos\Municipio\ServicoMunicipio;

class MunicipioHandler {
    private ServicoMunicipio $servico;
    public function __construct() {
        $this->servico = new ServicoMunicipio();
    }

    public function handler(): array {
        return $this->carregarMunicipios();
    }

    public static function carregarMunicipios(): array {
        $retorno = [];
        $ok =  ServicoMunicipio::carregarMunicipios();
        if (!$ok) {
         $retorno["status"] = 0;
         $retorno["mensagem"] = "Erro ao carregar municipios!";
        }
        return $retorno;
    }
}
<?php 

namespace Src\Infraestrutura\Web\Controladores\Arquivo;

use Src\Infraestrutura\Web\Dtos\ArquivoDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class arquivo_controlador {

    public ArquivoDto $arquivoDto;
    public function __construct() {


    }

    public function teste(Request $request):Response {
        $teste = $request->get("teste");
        var_dump($teste);
        return new Response($teste);
    }

    
}
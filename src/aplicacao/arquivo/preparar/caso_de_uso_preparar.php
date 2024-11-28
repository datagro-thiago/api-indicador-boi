<?php

namespace Src\Input\Aplicacao\Arquivo\Preparar;

use Src\Dominio\Arquivo;
use Symfony\Component\HttpFoundation\File\File;

class caso_de_uso_preparar {


    //TODO: Implementar preparação
    public function prepara(object $object): Arquivo {
        var_dump($object);

        
        $arquivoFile = new File($object);
        $arquivoFile->getFilename();
        $arquivo = Arquivo::novo($object);     
        return $arquivo;
        
    }

    public function gerarArquivoPreparado(
        string $remetente,
        string $getInfo,
        string $postInfo,
        string $fileInfo,
    ): string {

        $recebidos = "." . '/recebidos';

        if (!file_exists($recebidos)) { 
            mkdir($recebidos,0777, true);
        }

        $recebidos = "/" . date (format:"Y"); if (!file_exists($recebidos))  mkdir($recebidos,0777, true);
        $recebidos .= "/" . date ("m");		if (! file_exists ($recebidos)) mkdir ($recebidos);
        
        $fp = fopen($recebidos . "/" . $remetente . "." . date ("Y-m-d-H-i-s") . ".in" . ".log", "a");

        fwrite ($fp, "GET:\n" . $getInfo . "\n");
		fwrite ($fp, "POST:\n" . $postInfo . "\n");
		fwrite ($fp, "FILES:\n" . $fileInfo . "\n");

        fwrite ($fp, "GET:\n" . var_export ($_GET, true) . "\n");
		fwrite ($fp, "POST:\n" . var_export ($_POST, true) . "\n");
		fwrite ($fp, "FILES:\n" . var_export ($_FILES, true) . "\n");


        fclose ($fp);
        
        return $recebidos;
    }
}
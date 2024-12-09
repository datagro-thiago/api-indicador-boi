<?php

namespace Src\Aplicacao\Arquivo;

//Aqui constroi servicos referente a manipulação de arquivos recebidos
class Arquivo {
    
    
    public static function salvarArquivoLocalmente(string $caminhoLog, string $nomeArquivo, string $caminhoArquivo, string $remetente): array {

        $retorno = [];
        $extensao = strtoupper(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
        $mensagem = "";

        if ($extensao == "JSON" || $extensao == "XLS" || $extensao == "XLSX") {
            $importar = $caminhoLog . "/Importar";
            if (!file_exists($importar)) {
                mkdir($importar, 0777, true);
            }
    
            $destinoAtual = $caminhoArquivo; // Caminho temporário
            $destinoFinal = $importar . '/' . $remetente . "." . $nomeArquivo; // Caminho final
    
            if (!move_uploaded_file($destinoAtual, $destinoFinal)) {
                $mensagem .= "Falha ao mover o Negocio para $destinoFinal";
            }
            $retorno["status"] = 1;
            $retorno["mensagem"] = $mensagem;
            $retorno["caminhoArquivo"] = $destinoFinal;
    
            return $retorno;

        }

        $retorno["status"] = 0;
        $retorno["mensagem"] = "Somente processados os tipos XLS* e JSON.";
        return $retorno;


    }

} 
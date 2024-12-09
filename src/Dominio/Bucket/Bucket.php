<?php 
namespace Src\Dominio\Bucket;
class Bucket {
    public string $nome;
    public string $arquivo;
    public string $nomeDoArquivoBucket;
    


    public function __construct (string $nome, string $arquivo, string $nomeDoArquivoBucket) {
        $this->nomeDoArquivoBucket = $nomeDoArquivoBucket;
        $this->nome = $nome;
        $this->arquivo = $arquivo;
    }

    public function getNomeDoArquivoBucket () : string {
        return $this->nomeDoArquivoBucket;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getArquivo(): string {
        return $this->arquivo;
    }

}
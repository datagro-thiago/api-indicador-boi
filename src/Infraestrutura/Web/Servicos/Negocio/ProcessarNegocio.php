<?php

namespace Src\Infraestrutura\Web\Servicos\Negocio;

use DateTime;
use Exception;
use Shuchkin\SimpleXLSX;
use Src\Dominio\Bucket\Bucket;
use Src\Dominio\Negocio\Enum\FonteEnum;
use Src\Dominio\Negocio\Enum\FreteEnum;
use Src\Dominio\Negocio\Enum\ModalidadeEnum;
use Src\Dominio\Negocio\Enum\OperacaoEnum;
use Src\Dominio\Negocio\Negocio;

use Src\Infraestrutura\Bd\Persistencia\Arquivo\ArquivoRepositorio;
use Src\Infraestrutura\Bd\Persistencia\Negocio\NegocioRepositorio;

use Src\Infraestrutura\Web\Servicos\Arquivo\ServicoArquivo;
use Src\Infraestrutura\Web\Servicos\Bucket\S3;
use Src\Infraestrutura\Web\Servicos\Categoria\ServicoCategoria;
use Src\Infraestrutura\Web\Servicos\Industria\ServicoIndustria;
use Src\Infraestrutura\Web\Servicos\Municipio\ServicoMunicipio;
use Src\Infraestrutura\Web\Servicos\Planta\ServicoPlanta;
use Src\Infraestrutura\Web\Servicos\Raca\ServicoRaca;
use Symfony\Component\Console\Exception\RuntimeException;

//AQUI SE ENCONTRA O CORACAO DA APLICACAO
class ProcessarNegocio
{
    private NegocioRepositorio $negocioRepositorio;
    // private ServicoIndustria $buscarIndustria;
    private ServicoArquivo $servicoArquivo;

    public function __construct()
    {
        $this->negocioRepositorio = new NegocioRepositorio();
        // $this->buscarIndustria = new ServicoIndustria();
        $this->servicoArquivo = new ServicoArquivo();
    }
    public function processar(
        string $lote,
        string $remetente,
        string $job,
        string $caminhoLogs,
        string $arquivo
    ): array {

        $fonte = "AC";
        $negocios = array();
        $base = basename($lote);
        $tipoArquivo = strtoupper(pathinfo($base, PATHINFO_EXTENSION));
        $idIndustria = 1;

        $categoriaCache = [];
        $municipioCache = [];
        $racaCache = [];
        $plantaCache = [];
        $idArquivo = "";


        if ($idIndustria != 0) {
            $fonte = "I";
        }
        
        $dados = $this->servicoArquivo->prepararArquivo($tipoArquivo, $lote, $base);

        foreach ($dados as $chave => $valor) {
            $chaveMaiuscula = strtoupper($chave);

            if ($chaveMaiuscula === "ERRO") {
                return $negocios[] = $valor;
            }

            if ($chaveMaiuscula === "NEGOCIOS") {
                // Processa 'NEGOCIOS'
                foreach ($valor as $negocio) {
                    $idArquivo = isset($negocio["arquivo"]) ? ($negocio["arquivo"]) : "";

                    $planta = isset($negocio["planta"]) ? $negocio["planta"] : "";
                    if (!isset($plantaCache[$planta])) {
                        $id = ServicoPlanta::buscarPlanta($planta);
                        $plantaCache [$planta]= $id;
                    }

                    $raca = isset($negocio["raca"]) ? $negocio["raca"] : ""; 
                    if (!isset($racaCache[$raca])) { // Verificação de cache
                        $id = ServicoRaca::buscarRaca($raca);
                        $racaCache[$raca] = $id;
                    }

                    $categoria = isset($negocio["categoria"]) ? $negocio["categoria"] : ""; 
                    if (!isset($categoriaCache[$categoria])) { // Verificação de cache
                        $id = ServicoCategoria::buscarCategoria($categoria);
                        $categoriaCache[$categoria] = $id;
                    }

                    $origem = $negocio["origem"] ?? 0;
                    $destino = $negocio["destino"] ?? 0;

                    if (!isset($municipioCache[$origem])) {
                        $municipioCache[$origem] = ServicoMunicipio::buscarMunicipio($origem);
                    }

                    if (!isset($municipioCache[$destino])) {
                        $municipioCache[$destino] = ServicoMunicipio::buscarMunicipio($destino);
                    }

                    $negocio = Negocio::novo(
                        new DateTime(),
                        $negocio["dataAprovacao"] ?? null,
                        null,
                        FonteEnum::isValid($fonte),
                        1,
                        $negocio["idNegocio"] ?? null,
                        new DateTime(),
                        isset($negocio["dtAbate"]) ? $negocio["dtAbate"] : "",
                        (int) ($negocio["quantidade"] ?? 0),
                        isset($negocio["operacao"]) ? OperacaoEnum::isValid($negocio["operacao"]) : "C",
                        isset($negocio["modalidade"]) ? ModalidadeEnum::isValid($negocio["modalidade"]) : "O",
                        "{ Bonus: " . ($negocio["bonus"] ?? null) . ", " . "vBonus: " . ($negocio["vbonus"] ?? null) . "}",
                        $categoriaCache[$categoria],
                        $racaCache[$raca],
                        isset($negocio["nutricao"]) ? $negocio["nutricao"] : "",
                        $municipioCache[$origem],
                        $municipioCache[$destino],
                        $plantaCache [$planta],
                        isset($negocio["frete"]) ? FreteEnum::isValid($negocio["frete"]) : "",
                        isset($negocio["funrural"]) ? ($negocio["funrural"]) : "",
                        isset($negocio["diasPagto"]) ? (int) ($negocio["diasPagto"]) : 0,
                        isset($negocio["valor"]) ? (float) ($negocio["valor"]) : 0,
                        isset($negocio["pesomodo"]) ? $negocio["pesomodo"] : null,
                        isset($negocio["pesopercent"]) ? (float) ($negocio["pesopercent"]) : 0,
                        isset($negocio["linha"]) ? (int) ($negocio["linha"]) : null,
                        $idArquivo,
                    );

                    array_push($negocios, $negocio);
                }

                $loteInfo["lote"] = [
                    "arquivo" => $arquivo,
                    "id" => $negocio->getArquivo(),
                    "data" => date("d-m-Y H:i:s"),
                    "negociosProcessados" => count($negocios)
                ];

                $processamento = $this->processarTudo(
                    $negocios,
                    $remetente,
                    $job,
                    $caminhoLogs,
                    $loteInfo,
                    $lote,
                );
            }
        }

        return $negocios[] = $processamento;
    }
    //recebi todos os dados, agora vou processar, gerar log, csv e subir no bucket
    public function processarTudo(
        array $negocios,
        string $remetente,
        string $job,
        string $caminhoLogs,
        array $loteInfo,
        string $lote
    ): array {
        $rastreio = array();
        $status = 1;
        $mensagem = array();

        try {
            $log = $this->gerarESalvarLog($caminhoLogs, $job, $remetente);

            $caminhoCsv = "";
            $persistiu = $this->persistirNegocio($negocios);
            array_push($rastreio, $persistiu["id"]);
            array_push($rastreio, $loteInfo);

            if ($persistiu["status"] == 0) {
                $status = 0;
                array_push($mensagem, "Erro ao persistir dados.");
            }

            foreach ($negocios as $negocio) {
                $csv = $this->gerarCsv($negocio, $remetente, $job);

                $caminhoCsv = $csv["csv"];
            }


            $enviarCsvBucket = $this->enviarArquivoBucket("csv", $csv["csv"]);
            $enviarLogBucket = $this->enviarArquivoBucket("log", $log);
            $enviarArquivoOriginalBucket = $this->enviarArquivoBucket((function () use ($lote): string {
                if (strtoupper(pathinfo($lote, PATHINFO_EXTENSION)) === strtoupper("json")) {
                    return "original/json";
                } else {
                    return "original/xls";
                }
            })(), $lote);

            if (!$enviarCsvBucket || !$enviarLogBucket || !$enviarArquivoOriginalBucket) {
                $status = 0;
                array_push($mensagem, "Erro ao enviar arquivos para o bucket.");
            } else {
                unlink($caminhoCsv);
                unlink($log);
                unlink($lote);
            }

            return [
                "log" => $log,
                "rastreio" => $rastreio,
                "mensagem" => $mensagem,
                "status" => $status
            ];
        } catch (Exception $e) {
            return [
                "status" => 0,
                "mensagem" => "Erro ao processar negócios. Detalhes: " . $e->getMessage(),
            ];
        }
    }

    public function enviarArquivoBucket(string $nomeDestino, string $arquivo): int
    {
        $s3 = new S3();
        $absoluteFilePath = realpath($arquivo);

        if (!file_exists($absoluteFilePath)) {
            echo "Erro: Arquivo nao encontrado.\n";
            return 0;
        }

        $bucket = new Bucket(
            getenv("BUCKET_NAME"),
            $absoluteFilePath,
            $nomeDestino,
        );

        $ok = $s3->transmitirArquivo($bucket);

        if (!$ok) {
            return 0;
        }

        return 1;
    }

    public function gerarCsv(Negocio $negocio, string $remetente, string $job): array
    {
        $linhaCsv = "";
        $camposCsv = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/negocios-input/config/negocio_csv.json"), true);

        $dir = $_SERVER['DOCUMENT_ROOT'] . "/negocios-input/src/Infraestrutura/Logs/csv";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $arq = $dir . "/" . $remetente . "-" . $job . ".csv";

        $fp = fopen($arq, 'a+');
        if (!flock($fp, LOCK_EX)) {
            $result['status'] = "erro";
            $result['mensagem'] = "Erro ao obter trava exclusiva no arquivo CSV.";
            fclose($fp);
            return $result;
        }

        if (ftell($fp) == 0) {
            $colunasString = "";
            foreach ((array) $camposCsv["csv"] as $coluna) {
                $colunasString .= '"' . $coluna . '",';
            }
            $colunasString = rtrim($colunasString, ",");
            fwrite($fp, $colunasString . "\n");
        }

        foreach ($camposCsv["csv"] as $campoCSV) {
            $metodoGetter = "get" . ucfirst($campoCSV);
            if (method_exists($negocio, $metodoGetter)) {
                $valor = $negocio->$metodoGetter();
            } else {
                $valor = "";
            }
            $linhaCsv .= '"' . $valor . '",';
        }


        $linhaCsv = rtrim($linhaCsv, ",") . "\n";
        fwrite($fp, $linhaCsv);



        flock($fp, LOCK_UN); // Release the lock
        fclose($fp);


        $result['csv'] = $arq;
        $result['status'] = "sucesso";
        $result['mensagem'] = "CSV gerado e salvo com sucesso.";

        return $result;
    }

    public function gerarESalvarLog($dirBase, $job, $remetente): string
    {
        $recebidos = $dirBase . "/recebidos" . "/" . date("Y/m");

        if (!file_exists($recebidos)) {
            mkdir($recebidos, 0777, true);
        }

        $logFileName = $remetente . "." . $job . ".in.log";  // Construct the file name
        $logFilePath = $recebidos . "/" . $logFileName;

        $fp = fopen($logFilePath, "a");
        fwrite($fp, "POST:\n" . var_export($_POST, true) . "\n");
        fwrite($fp, "FILES:\n" . var_export($_FILES, true) . "\n");
        fclose($fp);

        return $logFilePath;
    }

    public function persistirNegocio(array $negocios): array
    {

        $ok = $this->negocioRepositorio->salvar($negocios);
        return [
            "id" => $ok["id"],
            "status" => $ok["status"]
        ];
    }

    public function buscarNegocio(string $id, string $senha): array
    {
        $retorno = [];
        $validar = $this->negocioRepositorio->buscarSenha($senha);

        if ($validar) {
            $negocio = $this->negocioRepositorio->buscar($id);

            $retorno = ["status" => 1, "negocio" => $negocio];
        } else {
            $retorno = ["status" => 0, "mensagem" => "Acesso negado!"];
        }

        return $retorno;
    }
}

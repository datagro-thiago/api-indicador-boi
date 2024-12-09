<?php

namespace Src\Infraestrutura\Web\Servicos\Negocio;

use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Src\Dominio\Bucket\Bucket;
use Src\Dominio\Negocio\Enum\FonteEnum;
use Src\Dominio\Negocio\Enum\FreteEnum;
use Src\Dominio\Negocio\Enum\ModalidadeEnum;
use Src\Dominio\Negocio\Enum\OperacaoEnum;
use Src\Dominio\Negocio\Negocio;

use Src\Infraestrutura\Bd\Persistencia\Negocio\NegocioRepositorio;

use Src\Infraestrutura\Web\Servicos\Categoria\BuscarCategoria;
use Src\Infraestrutura\Web\Servicos\Bucket\S3;
use Src\Infraestrutura\Web\Servicos\Industria\BuscarIndustria;
use Src\Infraestrutura\Web\Servicos\Municipio\BuscarMunicipio;
use Symfony\Component\Console\Exception\RuntimeException;

class ProcessarNegocio
{
    private NegocioRepositorio $negocioRepositorio;
    private BuscarIndustria $buscarIndustria;

    public function __construct()
    {
        $this->negocioRepositorio = new NegocioRepositorio();
        $this->buscarIndustria = new BuscarIndustria();
    }

    public function processar(string $caminhoNegocio, string $remetente, string $job, string $caminhoLogs): array
    {
        $fonte = "AC";
        $negocios = array();
        $base = basename($caminhoNegocio);
        $tipoArquivo = strtoupper(pathinfo($base, PATHINFO_EXTENSION));

        $idIndustria = $this->buscarIndustria->buscarIndustria($remetente);
        if ($idIndustria != 0) {
            $fonte = "I";
        }
        $dados = $this->prepararArquivo($tipoArquivo, $caminhoNegocio);
        $agora = new \DateTime();
        $categoriaCache = [];
        $municipioCache = [];

        foreach ($dados as $chave => $valor) {
            $chaveMaiuscula = strtoupper($chave);

            if ($chaveMaiuscula === "NEGOCIOS") {
                // Processa 'NEGS'
                foreach ($valor as $negocio) {

                    $categoria = $negocio["categoria"] ?? "";
                    if (!isset($categoriaCache[$categoria])) {
                        $categoriaCache[$categoria] = BuscarCategoria::buscarCategoria($categoria);
                    }
                
                    $origem = $negocio["origem"] ?? 0;
                    $destino = $negocio["destino"] ?? 0;
    
                    if (!isset($municipioCache[$origem])) {
                        $municipioCache[$origem] = BuscarMunicipio::buscarMunicipio($origem);
                        
                    }
                    if (!isset($municipioCache[$destino])) {
                        $municipioCache[$destino] = BuscarMunicipio::buscarMunicipio($destino);
                    }

                    $negocio = Negocio::novo(
                        $agora,
                        $negocio["dataAprovacao"] ?? null,
                        null,
                        FonteEnum::isValid($fonte),
                        1,
                        $negocio["idNegocio"] ?? null,
                        new \DateTime(),
                        isset($negocio["dtAbate"]) ? $negocio["dtAbate"] : "",
                        (int) ($negocio["quantidade"] ?? 0),
                        isset($negocio["operacao"]) ? OperacaoEnum::isValid($negocio["operacao"]) : "C",
                        isset($negocio["modalidade"]) ? ModalidadeEnum::isValid($negocio["modalidade"]) : "O",
                        "{" . ($negocio["bonus"] ?? null) . "," . ($negocio["vbonus"] ?? null) . "}",
                        $categoriaCache[$categoria],
                        (int) ($negocio["raca"] ?? 0),
                        (int) ($negocio["nutricao"] ?? 0),
                        $municipioCache[$origem],
                        $municipioCache[$destino],
                        isset($negocio["planta"]) ? 1 : 1,
                        isset($negocio["frete"]) ? FreteEnum::isValid($negocio["frete"]) : "",
                        isset($negocio["funrural"]) ? ($negocio["funrural"]) : "",
                        isset($negocio["diasPagto"]) ? (int) ($negocio["diasPagto"]) : 0,
                        isset($negocio["valor"]) ? (float) ($negocio["valor"]) : 0,
                        isset($negocio["pesomodo"]) ? $negocio["pesomodo"] : null,
                        isset($negocio["pesopercent"]) ? (float) ($negocio["pesopercent"]) : 0,
                    );

                    array_push($negocios, $negocio);
                }
            }
        }

        $processamento = $this->processarTudo($negocios, $remetente, $job, $caminhoLogs);

        return $negocios[] = $processamento;
    }

    //recebi todos os dados, agora vou processar, gerar log, csv e subir no bucket
    public function processarTudo(array $negocios, string $remetente, string $job, string $caminhoLogs): array
    {
        $rastreio = array();
        $status = 1;
        $mensagem = array();
        try {
            $log = $this->gerarESalvarLog($caminhoLogs, $job, $remetente);

            $negociosProcessados = [];
            $caminhoCsv = "";
            $persistiu = $this->persistirNegocio($negocios);
            array_push($rastreio, $persistiu["id"]);

            if ($persistiu["status"] == 0) {
                $status = 0;
                array_push($mensagem, "Erro ao persistir dados.");
            }

            foreach ($negocios as $negocio) {
                $csv = $this->gerarCsv($negocio, $remetente, $job);

                $caminhoCsv = $csv["csv"];
                $negociosProcessados[] = [
                    "csv" => $csv["csv"],
                ];
            }

            $negociosProcessados[] = [
                "totalNegocios" => count($negocios),
            ];

            $enviarCsvBucket = $this->enviarArquivoBucket("csv", $csv["csv"]);
            $enviarLogBucket = $this->enviarArquivoBucket("log",  $log);

            if (!$enviarCsvBucket || !$enviarLogBucket) {
                $status = 0;
                array_push($mensagem, "Erro ao enviar arquivos para o bucket.");
            } else {
                unlink($caminhoCsv);
                unlink($log);
            }

            return [
                "log" => $log,
                "negociosProcessados" => $negociosProcessados,
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
            foreach ((array)$camposCsv["csv"] as $coluna) {
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

    public function prepararArquivo(string $tipo, string $arquivo)
    {
        if ($tipo === "JSON") {
            $dados = json_decode(file_get_contents($arquivo), true, 512, JSON_THROW_ON_ERROR);
            return $dados ?: [];
        }
    
        if ($tipo === "XLS" || $tipo === "XLSX") {
            if (!file_exists($arquivo)) {
                throw new RuntimeException("Arquivo não encontrado: $arquivo");
            }
    
            $arquivoXls = IOFactory::load($arquivo);
            $planilha = $arquivoXls->getActiveSheet();
            
            $mapaColunas = [
                'codigo' => 'idNegocio',
                'Data Abate' => 'dtAbate',
                'Qtd. Produto' => 'quantidade',
                'Negociação' => 'operacao',
                'Mercado' => 'modalidade',
                'Valor' => 'valor',
                'Prazo Pagamento (Dias)' => 'diasPagto',
                'Raça' => 'raca',
                'Estado (Fazenda)' => 'origem',
                'Cidade (Fazenda)' => 'destino',
                'Cód. Empresa' => 'idIndustria',
                'Descrição Produto' => 'categoria',
                'Orgânico' => 'nutricao',
                'Valor Frete (Estimativa)' => 'frete',
                'Valor Premiação Cobertura' => 'bonus',
                'Valor Premiação Angus' => 'vbonus',
            ];
    
            $dados = [];
            $headers = [];
            $l= 0;
            $v = 0;
            $contLinha = $planilha->getHighestRow();
            $contColuna = $planilha->getHighestColumn();
            $array = $planilha->rangeToArray("A1:{$contColuna}{$contLinha}", null);
            
            foreach ($array as $index => $linha){
                $linhaDados = [];
                //checar por linhas vazias
                if(count(array_filter($linha, function($valor) {
                    return $valor !== null;
                })) == 0 ) {
                    continue;
                }

                if($index === 0) {
                    $headers[$index] = $linha;     
                } else {
                    $header = $headers[$index] ?? null;

                    if($header && isset($mapaColunas[$header])) {
                        $linhaDados[$mapaColunas[$header]] = $linha;
                    }
                }

                if ($index > 1) {
                    $dados[] = $linhaDados;
                }

            }

            
            var_dump($headers);


            
            
            
            
            // // Processar linha por linha
            // foreach ($planilha->getRowIterator() as $linhaIndex => $linha) {
            //     $cellIterator = $linha->getCellIterator();  
            //     $cellIterator->setIterateOnlyExistingCells(true);
            //     $l++;
            //     $linhaDados = [];
            //     $linhaVazia = true; // Assumimo que a linha está vazia até que tenha valores
            //     foreach ($cellIterator as $colunaIndex => $cell) {
            //         $valor = $cell->getValue();
            //         $v++;
            //         if ($linhaIndex === 1) { // Capturar cabeçalhos
            //             $headers[$colunaIndex] = trim((string)$valor); // Remover espaços
            //         } else { // Processar dados
            //             $header = $headers[$colunaIndex] ?? null;
                        
            //             if ($header && isset($mapaColunas[$header])) {
            //                 $linhaDados[$mapaColunas[$header]] = trim((string)$valor); // Remover espaços
            //                 if ($valor !== null && $valor !== '') {
            //                     $linhaVazia = false; // Marcar como não vazia se algum valor for encontrado
            //                 }
            //             }
            //         }
            //     }
                
            //     // Adicionar apenas linhas com dados válidos
            //     if ($linhaIndex > 1 && !$linhaVazia) {
            //         $dados[] = $linhaDados;
            //     }
            // }
            var_dump("Primeira iteracao: " . $l . " Segunda iteracao: " . $v);
    
            return ['NEGOCIOS' => $dados];
        }
    
        throw new RuntimeException("Tipo de arquivo não suportado: $tipo");
    }
    
        
}

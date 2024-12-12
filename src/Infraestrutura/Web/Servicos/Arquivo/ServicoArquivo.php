<?php
namespace Src\Infraestrutura\Web\Servicos\Arquivo;

use Exception;
use RuntimeException;
use Shuchkin\SimpleXLSX;
use Src\Dominio\Arquivo\Arquivo;
use Src\Infraestrutura\Bd\Persistencia\Arquivo\ArquivoRepositorio;

class ServicoArquivo {
    private ArquivoRepositorio $repositorio;

    public function __construct() {
        $this->repositorio = new ArquivoRepositorio();
    }
    public function persistirArquivo(string $nome, string $tipo): string {
        $arquivo = new Arquivo(
            $nome,
            $tipo,
            date("d-m-Y H:i:s")
        );
        $id = $this->repositorio->salvar($arquivo);
        return $id;
    }

    public function prepararArquivo(string $tipo, string $nome, string $base): mixed
    {
        $id = $this->persistirArquivo($base, $tipo);
        
        if ($tipo === "JSON") {
            try {
                $dados = json_decode(file_get_contents($nome), true, 512, JSON_THROW_ON_ERROR);
                // Verifica se 'NEGOCIOS' existe no array $dados
                if (isset($dados['negocios']) && is_array($dados['negocios'])) {
                    foreach ($dados['negocios'] as &$negocio) { 
                        $negocio['arquivo'] = $id;
                    }
                    unset($negocio);
                } else {
                    echo "A chave 'NEGOCIOS' não foi encontrada no arquivo JSON.";
                }
                return $dados;
            } catch (Exception $e) {
                echo "Erro inesperado: " . $e->getMessage();
            }
            
        }

        if ($tipo === "XLS" || $tipo === "XLSX") {
            if (!file_exists($nome)) {
                throw new RuntimeException("Arquivo não encontrado: $nome");
            }

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
                'Condição bovino' => 'categoria',
                'Orgânico' => 'nutricao',
                'Valor Frete (Estimativa)' => 'frete',
                'Valor Premiação Cobertura' => 'bonus',
                'Valor Premiação Angus' => 'vbonus',
                'Terminação' => 'nutricao',
            ];

            $dados = [];
            $headers = [];
            if ($planilha = SimpleXLSX::parseFile($nome)) {
                foreach ($planilha->readRows() as $index => $linha) {

                    if ($index === 0) {
                        foreach ($linha as $colunaIndex => $header) {
                            array_push($headers, $header); // Amazena os cabecalhos
                        }
                    } else {
                        // Processar os dados das linhas seguintes
                        foreach ($linha as $colunaIndex => $valor) {
                            $header = $headers[$colunaIndex] ?? null; // Obter o cabeçalho correspondente
                            if ($header and isset($mapaColunas[$header])) {
                                $linhaDados[$mapaColunas[$header]] = trim((string) $valor); // Remover espaços
                            }
                        }
                        $linhaDados['linha'] = $index + 1;
                        $linhaDados['arquivo'] = $id;
                        // Verifica se a chave "codigo" existe e se o valor é diferente de 0
                        if (!empty($linhaDados) && (!isset($linhaDados['idNegocio']) || $linhaDados['idNegocio'] != 0)) {
                            $dados[] = $linhaDados;
                        }
                    }

                }

                return ['NEGOCIOS' => $dados];
            } else {
                return ['erro' => SimpleXLSX::parseError()];
            }
        }

        throw new RuntimeException("Tipo de arquivo não suportado: $tipo");
    }


        
    public static function salvarArquivoLocalmente(string $caminhoLog, string $nomeArquivo, string $caminhoArquivo, string $remetente, string $job): array {

        $retorno = [];
        $extensao = strtoupper(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
        $mensagem = "";

        if ($extensao == "JSON" || $extensao == "XLS" || $extensao == "XLSX") {
            $importar = $caminhoLog . "/Importar";
            if (!file_exists($importar)) {
                mkdir($importar, 0777, true);
            }
    
            $destinoAtual = $caminhoArquivo; // Caminho temporário
            $destinoFinal = $importar . '/' . $remetente . "-" . $job . "." . $nomeArquivo; // Caminho final
            $idLote = $remetente . "-" . $job . "." . $nomeArquivo;
            if (!move_uploaded_file($destinoAtual, $destinoFinal)) {
                $mensagem .= "Falha ao mover o Negocio para $destinoFinal";
            }
            $retorno["status"] = 1;
            $retorno["mensagem"] = $mensagem;
            $retorno["caminhoArquivo"] = $destinoFinal;
            $retorno["idLote"] = $idLote;
    
            return $retorno;

        }

        $retorno["status"] = 0;
        $retorno["mensagem"] = "Somente processados os tipos XLS* e JSON.";
        return $retorno;


    }

   

}
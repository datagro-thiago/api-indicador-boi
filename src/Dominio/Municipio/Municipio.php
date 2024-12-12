<?php

namespace Src\Dominio\Municipio;

class Municipio {

    private string $id;
    private string $nome;
    private string $estado;
    private string $pais;
    private string $pais_alpha2;
    private string $pais_num;
    private string $slug;

    private static array $municipiosCache = []; // Armazena os municípios em memória

    public function __construct(
        string $id,
        string $nome,
        string $estado,
        string $pais,
        string $pais_alpha2,
        string $pais_num,
        string $slug
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->estado = $estado;
        $this->pais = $pais;
        $this->pais_alpha2 = $pais_alpha2;
        $this->pais_num = $pais_num;
        $this->slug = $slug;
    }

    public static function carregarMunicipiosDaApi(): bool {
        $url = "https://precos.api.datagro.com/basics/municipios.php?aliases=true&";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecionamentos

        $resposta = curl_exec($ch);
        $data = json_decode($resposta, true);
        $municipios = [];

        if (isset($data['municipios'])) {
            $municipios = $data['municipios'];
        }

        if (curl_errno($ch)) {
            echo 'Erro cURL: ' . curl_error($ch);
            return false;
        }

        curl_close($ch);

        // Armazenar os dados em memória
        self::$municipiosCache = array_map(function ($item) {
            return new self(
                $item['id'],
                $item['nome'],
                $item['estado'],
                $item['pais'],
                $item['pais_alpha2'],
                $item['pais_num'],
                $item['slug']
            );
        }, $municipios);

        return true;
    }

    public static function formatarNome(string $nome): string {
        // Remove tudo após o traço, converte para minúsculas e remove os espaços
        $nomeSemEstado = explode(' - ', $nome)[0]; // Pega tudo antes do traço
        $nomeSemEspacosETraços = str_replace([' ', '-'], '', $nomeSemEstado); // Remove espaços e traços
        
        return strtolower($nomeSemEspacosETraços); // Retorna em minúsculas
    }

    public static function formatarNomeSemEstado(string $nome): string {
        // Remove tudo após o traço principal, se existir
        $nomeSemEstado = explode(' - ', $nome)[0]; // Pega tudo antes do traço principal
        
        // Remove espaços ao redor de palavras pequenas (1 a 3 caracteres)
        $nomeSemEspacosExtras = preg_replace('/\s+(\b\w{1,3}\b)\s+/', '$1', $nomeSemEstado);
        
        // Remove traços das palavras (ex: "ji-parana" -> "jiparana")
        $nomeSemTracos = str_replace('-', '', $nomeSemEspacosExtras);
        
        // Converte para minúsculas e remove espaços ao redor
        return strtolower(trim($nomeSemTracos));
    }

    public static function buscarPorNome(string $nome): ?self {
        foreach (self::$municipiosCache as $municipio) {
            if (strtolower($municipio->getNome()) === strtolower($nome)) {
                return $municipio;
            }
        }
        return null; 
    }

    public static function buscarPorEstado(string $estado): array {
        return array_filter(self::$municipiosCache, function ($municipio) use ($estado) {
            return strtolower($municipio->getEstado()) === strtolower($estado);
        });
    }

    public static function buscarPorSlug(string $slug): ?self {
        foreach (self::$municipiosCache as $municipio) {
            if (strtolower($municipio->getSlug()) === strtolower($slug)) {
                return $municipio;
            }
        }
        return null;
    }


    public function getId(): string {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getPais(): string {
        return $this->pais;
    }

    public function getPaisAlpha2(): string {
        return $this->pais_alpha2;
    }

    public function getPaisNum(): string {
        return $this->pais_num;
    }

    public function getSlug(): string {
        return $this->slug;
    }
    
}

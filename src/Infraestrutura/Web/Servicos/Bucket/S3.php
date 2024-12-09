<?php

namespace Src\Infraestrutura\Web\Servicos\Bucket;

use Aws\Result;
use Aws\S3\S3Client;
use Src\Dominio\Bucket\Bucket;
use Src\Dominio\Bucket\BucketGateway;

class S3 implements BucketGateway {
    private S3Client $s3;

    public function __construct() {
        $this->configurarBucketS3();
    }

    public function configurarBucketS3(): S3Client {

        return $this->s3 = new S3Client([
            'region' => getenv('AWS_REGION'),
            'credentials' => [
                'key' => getenv('AWS_USER_KEY'),
                'secret'=> getenv('AWS_USER_SECRET'),
            ]
        ]);
    }

    public function listarBuckets(): Result {
        $buckets = $this->s3->listBuckets();
        return $buckets;
    }

    public function listarObjetosBucket(Bucket $bucket): mixed {
        $body = $this->s3->listObjectsV2([
            'Bucket' => $bucket->getNome(),
        ]);

        foreach ($body['Contents'] as $content) {
            echo $content['Key'] . "\n";
        }
        return $body;

    }
    
    public function transmitirArquivo(Bucket $bucket): int {
        try {
            $this->s3->putObject([
                'Bucket' => $bucket->getNome(),
                'Key' => $bucket->getNomeDoArquivoBucket() . '/' . basename($bucket->getArquivo()),
                'SourceFile' => $bucket->getArquivo(),
            ]);
            
            return 1;

        } catch (\Exception $e) { 
            echo "Falha ao fazer o upload do arquivo", $e->getMessage(),"";
            return 0;
        }
    }
}
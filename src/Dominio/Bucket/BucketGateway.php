<?php

namespace Src\Dominio\Bucket;
interface BucketGateway {
    public function transmitirArquivo(Bucket $bucket);
}
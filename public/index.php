<?php

use Src\Input\Infraestrutura\Web\Rotas\Input;

include $_SERVER ['DOCUMENT_ROOT'] . "/input/src/infraestrutura/web/rotas/" . "Input.php";

require_once $_SERVER['DOCUMENT_ROOT']. '/input/vendor/autoload.php';



$input = new Input();
$response = $input->configurar();
$response->send();
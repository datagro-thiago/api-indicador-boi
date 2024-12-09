<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/negocios-input/vendor/autoload.php';


use Src\Infraestrutura\Web\Config\Input;

// include $_SERVER ['DOCUMENT_ROOT'] . "/input/src/infraestrutura/web/rotas/" . "Input.php";

$dotenv = Dotenv\Dotenv::createUnsafeImmutable($_SERVER['DOCUMENT_ROOT']. '/negocios-input');
$dotenv->load();



$input = new Input();
$response = $input->configurar();
$response->send();
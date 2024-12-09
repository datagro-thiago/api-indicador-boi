<?php

namespace Src\Infraestrutura\Web\Config;

define('ROTAS',$_SERVER['DOCUMENT_ROOT'] . '/negocios-input/config/routes.json');

use Src\Dominio\Municipio\Municipio;
use Src\Infraestrutura\Web\Servicos\Categoria\BuscarCategoria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

class Input
{
    private RouteCollection $rotas;

    public function __construct()
    {
        $this->rotas = new RouteCollection();
        $this->carregarRotas(ROTAS);
        Municipio::carregarMunicipiosDaApi();
        // BuscarCategoria::carregarCategorias();
    }
    private function carregarRotas(string $caminho): void{
        $rotas = json_decode(file_get_contents($caminho), true);

        foreach ($rotas as $rota) {
            $this->definirRotas(
                $rota['nome'],
                $rota['pacote'],
                $rota['controlador'],
                $rota['metodos']
            );
        }
    }

    private function definirRotas(
        string $nome,
        string $pacote,
        string $controlador,
        array $metodos
    ): void {
        $this->rotas->add(
            $nome,
            new SymfonyRoute(
                $pacote,
                ['_controller' => $controlador],
                [],
                [],
                '',
                [],
                $metodos
            )
        );
    }

    public function configurar(): Response
    {
        $request = Request::createFromGlobals();
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->rotas, $context);
        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();
        try {
            $request->attributes->add($matcher->match($request->getPathInfo()));
            $controller = $controllerResolver->getController($request);
            $arguments = $argumentResolver->getArguments($request, $controller);
            return call_user_func_array($controller, $arguments);
        } catch (\Exception $e) {
            return new Response('Rota não encontrada', 404);
        }

    }

}

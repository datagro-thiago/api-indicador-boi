<?php

namespace Src\Input\Infraestrutura\Web\Rotas;
include $_SERVER['DOCUMENT_ROOT'] . "/input/src/aplicacao/arquivo/preparar/caso_de_uso_preparar.php";
include $_SERVER['DOCUMENT_ROOT'] . "/input/src/infraestrutura/web/controladores/arquivo/arquivo_controlador.php";

use Src\Input\Aplicacao\Arquivo\Preparar\caso_de_uso_preparar;
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
      // TODO: Corrigir caminho para arquivo_controlador routes.json

    private RouteCollection $routes;
    private caso_de_uso_preparar $preparar;
    public function __construct()
    {
        $this->routes = new RouteCollection();
        $routes= json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/input/config/routes.json'), true);
        foreach ((array)$routes as $route) {
            $this->defineRoutes(
                $route['name'],
                 $route['path'],
                  $route['controller'],
                   $route['methods']
            );
        }
    }

    private function defineRoutes(
        string $name,
         string $path,
          string $controller,
           array $methods
        ): void
    { 
        $this->routes->add(
                $name,
                new SymfonyRoute(
                    $path,
                    ['_controller' => $controller,],
                    [],
                    [],
                    '',
                    [] ,
                    $methods
                )
            );
    }

    public function configurar(): Response
    {
        $request = Request::createFromGlobals();
        $context = new RequestContext();
        $context->fromRequest($request);
        
        $matcher = new UrlMatcher($this->routes, $context);
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

<?php

namespace Src\Input\Infraestrutura\Web\Rotas;

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
    public function __construct()
    {
        $this->routes = new RouteCollection();
        $routes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/input/config/routes.json'), true);
        foreach ((array) $routes as $route) {
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
    ): void {
        $this->routes->add(
            $name,
            new SymfonyRoute(
                $path,
                ['_controller' => $controller],
                [],
                [],
                '',
                [],
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

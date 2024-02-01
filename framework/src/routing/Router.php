<?php


namespace Framework\Routing;


use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Framework\controller\AbstractController;
use Framework\http\exceptions\MethodNotAllowedException;
use Framework\http\exceptions\RouteNotFoundException;
use Framework\http\Request;
use League\Container\Container;
use function FastRoute\simpleDispatcher;


class Router implements RouterInterface
{
    private array $routes;

    /**
     * @throws RouteNotFoundException
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws MethodNotAllowedException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function dispatch(Request $request, Container $container): array
    {
        $handler = $request->getRouteHandler();
        $vars = $request->getRouteArgs();

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;

            $controller = $container->get($controllerId);

            $handler = [$controller, $method];
        }

        if (is_subclass_of($controller, AbstractController::class)){
            $controller->setRequest($request);
        }

        return [$handler, $vars];
    }

}
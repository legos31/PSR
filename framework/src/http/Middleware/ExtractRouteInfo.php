<?php


namespace Framework\Http\Middleware;


use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Framework\http\exceptions\MethodNotAllowedException;
use Framework\http\exceptions\RouteNotFoundException;
use Framework\http\Request;
use Framework\http\Response;
use function FastRoute\simpleDispatcher;

class ExtractRouteInfo implements MiddlewareInterface
{

    public function __construct(
        private $routes
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {

            foreach ($this->routes as $route) {
                $routeCollector->addRoute(...$route);
            }

        });
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath(),
        );


        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                $request->setRouteHandler($routeInfo[1][0]);
                $request->setRouteArgs($routeInfo[2]);
                $handler->injectMiddleware($routeInfo[1][1]);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode (',', $routeInfo[1]);
                $exception = new MethodNotAllowedException("Supported HTTP methods: ". $allowedMethods);
                $exception->setStatusCode('405');
                throw $exception;
            default:
                $exception = new RouteNotFoundException('Route not found');
                $exception->setStatusCode('404');
                throw $exception;
        }

        return $handler->handle($request);
    }
}
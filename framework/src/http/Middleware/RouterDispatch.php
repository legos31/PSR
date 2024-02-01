<?php


namespace Framework\Http\Middleware;


use Framework\http\Request;
use Framework\http\Response;
use Framework\routing\RouterInterface;
use League\Container\Container;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private Container $container
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
            [$handler, $vars] = $this->router->dispatch($request, $this->container);
            $response = call_user_func_array($handler, $vars);

            return $response;
    }
}
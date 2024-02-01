<?php


namespace Framework\Http\Middleware;


use Framework\http\Request;
use Framework\http\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware =[
        ExtractRouteInfo::class,
        SessionMiddleware::class,
        RouterDispatch::class
    ];
    /**
     * @inheritDoc
     */

    public function __construct(private ContainerInterface $container)
    {
    }

    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            return new Response('Server error, empty middleware');
        }


        /** @var MiddlewareInterface $middlewareClass */
        $middlewareClass = array_shift($this->middleware);

        $middleware = $this->container->get($middlewareClass);
        $response = $middleware->process($request, $this);

        return $response;

    }

    public function injectMiddleware($middleware): void
    {
        array_splice($this->middleware, 0, 0, $middleware);
    }
}
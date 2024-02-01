<?php


namespace Framework\http;

use Doctrine\DBAL\Connection;
use Framework\Event\EventDispatcher;
use Framework\http\exceptions\HttpException;
use Framework\Http\Middleware\RequestHandlerInterface;
use Framework\routing\RouterInterface;
use League\Container\Container;
use Framework\Http\Events\ResponseEvent;


class Kernel
{
    private string $appEnv = 'local';

    public function __construct(
        private Container $container,
        private RequestHandlerInterface $requestHandler,
        private EventDispatcher $eventDispatcher
    )
    {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle (Request $request) : Response
    {
        try {
            $response = $this->requestHandler->handle($request);
        } catch (\Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));
        return $response;
    }

    private function createExceptionResponse (\Exception $e)
    {
        if (in_array($this->appEnv, ['local', 'testing'])) {
            throw $e;
        }
        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }
        return new Response('Server error', 500);
    }

    public function terminate(Request $request, Response $response):void
    {
        $request->getSession()?->clearFlash();
    }
}
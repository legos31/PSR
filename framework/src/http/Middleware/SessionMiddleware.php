<?php


namespace Framework\Http\Middleware;


use Framework\http\Request;
use Framework\http\Response;
use Framework\Session\SessionInterface;

class SessionMiddleware implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session)
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();
        $request->setSession($this->session);
        return $handler->handle($request);
    }
}
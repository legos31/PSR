<?php


namespace Framework\Http\Middleware;


use Framework\http\Request;
use Framework\http\Response;

class Success implements MiddlewareInterface
{

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return new Response('Hello from middleware Success');
    }
}
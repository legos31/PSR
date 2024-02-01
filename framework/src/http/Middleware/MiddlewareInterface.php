<?php


namespace Framework\Http\Middleware;


use Framework\http\Request;
use Framework\http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}
<?php


namespace Framework\Http\Middleware;


use Framework\http\Request;
use Framework\http\Response;

interface RequestHandlerInterface
{
    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(Request $request): Response;

    public function injectMiddleware(array $middleware): void;
}
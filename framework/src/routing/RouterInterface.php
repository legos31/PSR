<?php


namespace Framework\routing;


use Framework\http\Request;
use League\Container\Container;

interface RouterInterface
{
    public function dispatch(Request $request, Container $container);

}
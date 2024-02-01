<?php
namespace Framework\controller;

use Framework\http\Request;
use Framework\http\Response;
use League\Container\Container;
use Psr\Container\ContainerInterface;
use Twig\Environment;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;
    protected Request $request;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render(string $view, array $parameters =[], Response $response = null) :Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get('twig');
        $content = $twig->render($view, $parameters);

        $response ??= new Response($content);
        $response->setContent($content);
        return $response;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
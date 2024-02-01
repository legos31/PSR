<?php

namespace Framework\Template;

use Framework\Session\SessionInterface;
use Framework\Authentication\SessionAuthInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private string $viewPath,
        private SessionInterface $session,
        private SessionAuthInterface $auth
    )
    {
    }

    public function create() :Environment
    {
        $loader = new FilesystemLoader($this->viewPath);
        $twig = new Environment($loader, [
           'debug' => true,
           'cache' => false
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));
        $twig->addFunction(new TwigFunction('auth', [$this, 'getAuth']));

        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }
    public function getAuth(): SessionAuthInterface
    {
        return $this->auth;
    }
}
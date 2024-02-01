<?php


namespace Framework\http;


use Framework\Session\SessionInterface;

class Request
{
    private SessionInterface $session;
    private $routeHandler;
    private $routeArgs;

    public function __construct (
        private array $getParams,
        public array $postData,
        private array $cookies,
        private array $files,
        public array $server,
        )
    {

    }

    public static function createFromGlobals (): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function input(string $key, mixed $default = null)
    {
        return $this->postData[$key] ?? $default;
    }

    /**
     * @return mixed
     */
    public function getRouteHandler()
    {
        return $this->routeHandler;
    }

    /**
     * @param mixed $routeHandler
     */
    public function setRouteHandler($routeHandler): void
    {
        $this->routeHandler = $routeHandler;
    }

    /**
     * @return mixed
     */
    public function getRouteArgs()
    {
        return $this->routeArgs;
    }

    /**
     * @param mixed $routeArgs
     */
    public function setRouteArgs($routeArgs): void
    {
        $this->routeArgs = $routeArgs;
    }

    public function getPath(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }
}
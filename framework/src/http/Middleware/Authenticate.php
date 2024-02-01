<?php


namespace Framework\Http\Middleware;


use Framework\Authentication\SessionAuthInterface;
use Framework\http\RedirectResponse;
use Framework\http\Request;
use Framework\http\Response;
use Framework\Session\SessionInterface;

class Authenticate implements MiddlewareInterface
{
    public function __construct(
        private SessionAuthInterface $auth,
        private SessionInterface $session
    )
    {
    }

    //private bool $auth = false;

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();
        if (!$this->auth->check()) {
            $this->session->setFlash('error', 'You are not auth');
            return new RedirectResponse('/login');
            return new Response('Auth filed!', 401);
        }

        return $handler->handle($request);
    }
}
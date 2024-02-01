<?php


namespace Framework\http;


class RedirectResponse extends Response
{
    public function __construct(string $url)
    {
        parent::__construct('', '302', ['location' => $url]);
    }

    public function send():void
    {
        header('location:'.$this->getHeader('location'), true, $this->getStatusCode());
        exit;
    }
}
<?php

namespace Framework\Http\Events;

use Framework\Event\Event;
use Framework\Http\Request;
use Framework\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private Request $request,
        private Response $response
    ) {
    }

public function getRequest(): Request
{
    return $this->request;
}

public function getResponse(): Response
{
    return $this->response;
}
}

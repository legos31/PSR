<?php

namespace App\Listeners;

use Framework\Http\Events\ResponseEvent;

class ContentLengthListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if (! array_key_exists('i-legos', $response->getHeaders())) {
            $response->setHeader('i-legos', strlen($response->getContent()));
        }


    }
}

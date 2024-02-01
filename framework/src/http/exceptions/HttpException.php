<?php


namespace Framework\http\exceptions;


class HttpException extends \Exception
{
    private int $statusCode = 400;

    public function setStatusCode (int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode ()
    {
        return $this->statusCode;
    }
}
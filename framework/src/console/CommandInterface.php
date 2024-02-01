<?php


namespace Framework\console;


interface CommandInterface
{
    public function execute(array $params = []): int;

}
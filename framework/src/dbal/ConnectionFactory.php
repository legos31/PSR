<?php


namespace Framework\dbal;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public function __construct(private string $dbUrl)
    {
    }

    public function create():Connection
    {
        return $connection = DriverManager::getConnection([
            'url' => $this->dbUrl
        ]);

    }
}
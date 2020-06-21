<?php

namespace App\Database\Driver;

use App\Configuration\Config;

class Connection implements ConnectionInterface
{
    protected ConnectionInterface $connection;

    private const DRIVERS = [
        'pdo_mysql' => PDODriver::class
    ];

    public function __construct()
    {
        if (null === $class = self::DRIVERS[Config::get('database_driver')] ?? null) {
            return;
        }

        $this->connection = new $class;
    }

    /**
     * @return ConnectionInterface
     */
    public static function getConnection(): ConnectionInterface
    {
        return (new static)->connection;
    }

    public function execute(string $sql)
    {
        return $this->connection->execute($sql);
    }

    /**
     * @param string $sql
     * @return array
     */
    public function query(string $sql): array
    {
        return $this->connection->query($sql);
    }
}

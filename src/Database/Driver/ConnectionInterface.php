<?php

namespace App\Database\Driver;

interface ConnectionInterface
{
    /**
     * @param string $sql
     * @return mixed
     */
    public function execute(string $sql);

    /**
     * @param string $sql
     * @return array
     */
    public function query(string $sql): array;
}

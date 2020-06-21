<?php

namespace App\Database\Driver;

class PDODriver implements ConnectionInterface
{
    /**
     * @var \PDO
     */
    protected \PDO $PDO;

    public function __construct()
    {
        $dsn = "mysql:dbname={$_ENV['DB_NAME']};host={$_ENV['DB_HOST']}";
        try {
            $this->PDO = new \PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            $this->PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {}
    }

    /**
     * @inheritDoc
     */
    public function execute($sql): bool
    {
        try {
            $statement = $this->PDO->prepare($sql);
            $this->PDO->beginTransaction();

            $statement->execute();

            $this->PDO->commit();
        } catch (\PDOException $exception) {
            if ($this->PDO->inTransaction()) {
                $this->PDO->rollBack();
            }
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function query(string $sql): array
    {
        /** @var \PDOStatement $statement */
        $statement = $this->PDO->query($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);

        return $statement->fetchAll();
    }
}

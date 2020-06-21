<?php

namespace App\Database;

use App\Database\Driver\Connection;
use App\Database\Driver\ConnectionInterface;
use App\DataMapper\EntityMapper;
use App\Entity\EntityInterface;

class DatabaseManager implements DatabaseManagerInterface
{
    protected ConnectionInterface $connection;

    /**
     * Get database connection.
     */
    public function getConnection(): void
    {
        $this->connection = Connection::getConnection();
    }

    /**
     * @inheritDoc
     */
    public function save(EntityInterface $entity, $options = []): bool
    {
        $this->getConnection();
        if (!isset($options['type'])) {
            $type = $entity->getId() === null ? QueryBuilder::INSERT : QueryBuilder::UPDATE;
        } else {
            $type = $options['type'];
        }

        $queryBuilder = (new QueryBuilder)
            ->setType($type)
            ->setTable($entity->getTable())
            ->setData($entity);

        if ($type === QueryBuilder::UPDATE) {
            $queryBuilder->setWhere("id = {$entity->getId()}");
        }

        return $this->connection->execute($queryBuilder->getSQL());
    }

    /**
     * @inheritDoc
     */
    public function delete(EntityInterface $entity, $where = ''): bool
    {
        $this->getConnection();

        $queryBuilder = (new QueryBuilder)
            ->setType(QueryBuilder::DELETE)
            ->setTable($entity->getTable());

        if ($where === '') {
            $where = 'id = ' . $entity->getId();
        }

        $queryBuilder->setWhere($where);

        return $this->connection->execute($queryBuilder->getSQL());
    }

    /**
     * @inheritDoc
     */
    public function findOne(EntityInterface $entity, $where = ''): ?EntityInterface
    {
        $queryBuilder = (new QueryBuilder)
            ->setType(QueryBuilder::SELECT);

        if ($where === '') {
            $where = 'id = ' . $entity->getId();
        }

        $queryBuilder
            ->setWhere($where)
            ->setTable($entity->getTable());

        $result = $this->query($queryBuilder->getSQL());

        if (\count($result) === 0) {
            return null;
        }

        return EntityMapper::map($entity, null,  $result[0]);
    }

    /**
     * @inheritDoc
     */
    public function findMany(EntityInterface $entity, $where = '', $orderBy = '', $limit = '', $offset = ''): array
    {
        $queryBuilder = (new QueryBuilder)
            ->setType(QueryBuilder::SELECT);

        $queryBuilder
            ->setWhere($where)
            ->setTable($entity->getTable())
            ->setOrderBy($orderBy)
            ->setLimit($limit)
            ->setOffset($offset);

        $results = $this->query($queryBuilder->getSQL());

        $entityArray = [];

        foreach ($results as $result) {
            $entityArray[] = EntityMapper::map(new $entity, null,  $result);
        }


        return [
            'data' => $entityArray,
            'count' => $results[0]['totalCount']
        ];
    }

    /**
     * @param string $SQL
     * @return array
     */
    protected function query(string $SQL): array
    {
        $this->getConnection();

        return $this->connection->query($SQL);
    }
}

<?php

namespace App\Database;

use App\Entity\EntityInterface;

interface DatabaseManagerInterface
{
    /**
     * @param EntityInterface $entity
     * @param array $option
     * @return bool
     */
    public function save(EntityInterface $entity, $option = []): bool;

    /**
     * @param EntityInterface $entity
     * @return bool
     */
    public function delete(EntityInterface $entity): bool;

    /**
     * @param EntityInterface $entity
     * @param string $where
     * @return EntityInterface|null
     */
    public function findOne(EntityInterface $entity, $where = ''): ?EntityInterface;

    /**
     * @param EntityInterface $entity
     * @param string $where
     * @param string $orderBy
     * @param string $limit
     * @param string $offset
     * @return array
     */
    public function findMany(EntityInterface $entity, $where = '', $orderBy = '', $limit = '', $offset = ''): array;
}

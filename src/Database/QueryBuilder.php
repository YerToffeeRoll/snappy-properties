<?php

namespace App\Database;

use App\Entity\EntityInterface;

class QueryBuilder
{
    public const SELECT = 'select';
    public const DELETE = 'delete';
    public const UPDATE = 'update';
    public const INSERT = 'insert';

    protected string $type;

    protected string $table;

    protected array $data = [];

    protected string $where = '';

    protected string $orderBy = '';

    protected string $limit = '';

    protected string $offset = '';

    /**
     * Gets the SQL for a database transaction.
     *
     * @return string
     */
    public function getSQL(): string
    {
        switch ($this->type) {
            case self::INSERT:
                $sql = $this->getInsertSQL();
                break;

            case self::DELETE:
                $sql = $this->getDeleteSQL();
                break;

            case self::UPDATE:
                $sql = $this->getUpdateSQL();
                break;

            case self::SELECT:
            default:
                $sql = $this->getSelectSQL();
                break;
        }

        return $sql;
    }

    /**
     * @param string $type
     * @return QueryBuilder
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $table
     * @return QueryBuilder
     */
    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param EntityInterface $entity
     * @return QueryBuilder
     * @throws \ReflectionException
     */
    public function setData(EntityInterface $entity): self
    {
        $this->data = $this->formatData($entity);
        return $this;
    }

    /**
     * @param string $where
     * @return QueryBuilder
     */
    public function setWhere(string $where): self
    {
        $this->where = $where;
        return $this;
    }

    /**
     * @param string $orderBy
     * @return QueryBuilder
     */
    public function setOrderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param string $limit
     * @return QueryBuilder
     */
    public function setLimit(string $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param string $offset
     * @return QueryBuilder
     */
    public function setOffset(string $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Get the select statement.
     *
     * @return string
     */
    protected function getSelectSQL(): string
    {
        return 'SELECT *, (SELECT COUNT(*) FROM ' . $this->table . ') AS totalCount FROM ' . $this->table
            . ($this->where !== '' ? ' WHERE ' . $this->where : '')
            . ($this->orderBy ? ' ORDER BY ' . $this->orderBy : '')
            . ($this->limit ? ' LIMIT ' . $this->limit : '')
            . ($this->offset ? ' OFFSET ' . $this->offset : '');
    }

    /**
     * Get the insert statement.
     *
     * @return string
     */
    protected function getInsertSQL(): string
    {
        return 'INSERT INTO ' . $this->table .
            ' (' . implode(', ', array_keys($this->data)) . ')' .
            ' VALUES(' . implode(', ', $this->data) . ')';
    }

    /**
     * Get the update statement.
     *
     * @return string
     */
    protected function getUpdateSQL(): string
    {
        $query = 'UPDATE ' . $this->table . ' SET';

        array_walk($this->data, static function ($value, $key) use(&$query) {
            $query .= " $key = $value,";
        });

        $query = rtrim($query, ',');

        $query .= ($this->where !== '' ? ' WHERE ' . $this->where : '');

        return $query;
    }

    /**
     * Get the delete statement.
     *
     * @return string
     */
    protected function getDeleteSQL(): string
    {
        return 'DELETE FROM ' . $this->table . ($this->where !== '' ? ' WHERE ' . $this->where : '');
    }

    /**
     * Formats the data that will be added to the database and sanatizes input.
     *
     * @param EntityInterface $entity
     * @return array
     * @throws \ReflectionException
     */
    protected function formatData(EntityInterface $entity): array
    {
        $class = new \ReflectionClass($entity);
        $properties = $class->getProperties();

        $data = [];
        foreach($properties as $property) {
            $method = $this->getMethod($property->getName());
            $propertyName = $property->getName();

            $value = $entity->{$method}();

            if ($entity->{$method}() instanceof EntityInterface) {
                $value = $value->getId();
                $propertyName .= 'Id';
            }

            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            if ($propertyName !== 'id' || $this->type !== self::INSERT || $class->name === 'App\Entity\PropertyType') {
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                $data[$propertyName] = "'$value'";
            }
        }

        return $data;
    }

    /**
     * @param $property
     * @return string
     */
    protected function getMethod($property): string
    {
        return 'get' . ucfirst($property);
    }
}

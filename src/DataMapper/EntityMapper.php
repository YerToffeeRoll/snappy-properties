<?php

namespace App\DataMapper;

use App\Database\DatabaseManager;
use App\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\Request;

class EntityMapper
{
    private static array $keyCache = [];

    protected EntityInterface $entity;

    /**
     * Mao the request or array to an entity.
     *
     * @param EntityInterface $entity
     * @param Request|null $request
     * @param array $data
     * @return EntityInterface
     * @throws \ReflectionException
     */
    public static function map(EntityInterface $entity, Request $request = null, $data = []): EntityInterface
    {
        $postValues = $request !== null ? $request->request->all() : $data;

        $entityMapper = new static;
        $entityMapper->entity = $entity;

        foreach($postValues as $key => $value) {
            $method = $entityMapper->getMethod($key);
            if (method_exists($entity, $method)) {
                $value = $entityMapper->getRelationship($entity, $method, $value);
                $entity->{$method}($value);
            }
        }

        return $entity;
    }

    /**
     * Gets a class parameters setter method.
     *
     * @param $key
     * @return string
     */
    protected function getMethod($key): string
    {
        if (!isset(self::$keyCache[$key])) {
            $words = explode('_', $key);
            $methodName = '';

            foreach ($words as $word) {
                $methodName .= ucfirst($word);
            }

            if (
                (null !== $relationship = $this->entity->getRelationship()) &&
                (
                    $methodName === ucfirst($relationship['joinColumn']) ||
                    $methodName === ucfirst($relationship['name'])
                )
            ) {
                $methodName = $relationship['name'];
            }

            self::$keyCache[$key] = 'set' . $methodName;
        }

        return self::$keyCache[$key];
    }

    /**
     * Gets the relationship of it exists.
     *
     * @param EntityInterface $entity
     * @param string $method
     * @param $value
     * @return EntityInterface|mixed|null
     * @throws \ReflectionException
     */
    protected function getRelationship(EntityInterface $entity, string $method, $value)
    {
        $methodData = new \ReflectionMethod($entity, $method);

        $parameter = $methodData->getParameters()[0];

        if (!empty($parameter->getClass()->name) && null !== $this->entity->getRelationship()) {
            $class = $parameter->getClass()->name;
            if (is_array($value)) {
                $value = $value['id'];
            }

            return (new DatabaseManager)->findOne((new $class)->setId($value));
        }

        return $value;
    }
}

<?php

namespace App\Entity;

use App\Configuration\Config;

abstract class AbstractEntity implements EntityInterface
{
    /**
     * @inheritDoc
     */
    public function getTable(): string
    {
        $className = get_class($this);

        return Config::get("entity.{$className}.table");
    }

    /**
     * @inheritDoc
     */
    public function getRelationship(): ?array
    {
        $className = get_class($this);

        return Config::get("entity.{$className}.relationship");
    }
}

<?php

namespace App\Entity;

interface EntityInterface
{
    /**
     * Returns the table when adding to the database.
     *
     * @return string
     */
    public function getTable(): string;

    /**
     * Gets the config for a many to one relationship.
     *
     * @return array|null
     */
    public function getRelationship(): ?array;

    /**
     * This is the default for retrieving a record from the database.
     *
     * @return int|null
     */
    public function getId(): ?int;
}

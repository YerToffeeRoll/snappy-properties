<?php

namespace App\Entity;

class PropertyType extends AbstractEntity
{
    protected ?int $id = null;

    protected string $title;

    protected string $description;

    protected \DateTime $createdAt;

    protected \DateTime $updatedAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return PropertyType
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return PropertyType
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return PropertyType
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     * @return $this
     * @throws \Exception
     */
    public function setCreatedAt($createdAt): self
    {
        if (is_string($createdAt)) {
            $createdAt = new \DateTime($createdAt);
        }

        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param $updatedAt
     * @return $this
     * @throws \Exception
     */
    public function setUpdatedAt($updatedAt): self
    {
        if (is_string($updatedAt)) {
            $updatedAt = new \DateTime($updatedAt);
        }

        if (null === $updatedAt) {
            $updatedAt = new \DateTime();
        }

        $this->updatedAt = $updatedAt;
        return $this;
    }
}

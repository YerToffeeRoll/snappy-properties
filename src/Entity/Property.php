<?php

namespace App\Entity;

class Property extends AbstractEntity
{
    protected ?int $id = null;

    protected string $county;

    protected string $country;

    protected string $town;

    protected ?string $postcode = null;

    protected string $description;

    protected string $address;

    protected ?string $longitude = null;

    protected ?string $latitude = null;

    protected string $imageFull;

    protected string $imageThumbnail = '';

    protected int $numBedrooms;

    protected int $numBathrooms;

    protected int $price;

    protected string $type;

    protected ?string $uuid = null;

    protected PropertyType $propertyType;

    protected \DateTime $createdAt;

    protected \DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Property
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCounty(): string
    {
        return $this->county;
    }

    /**
     * @param string $county
     * @return Property
     */
    public function setCounty(string $county): self
    {
        $this->county = $county;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Property
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getTown(): string
    {
        return $this->town;
    }

    /**
     * @param string $town
     * @return Property
     */
    public function setTown(string $town): self
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * @param string|null $postcode
     * @return Property
     */
    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;
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
     * @return Property
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Property
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param string|null $longitude
     * @return Property
     */
    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string|null $latitude
     * @return Property
     */
    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageFull(): string
    {
        return $this->imageFull;
    }

    /**
     * @param string $imageFull
     * @return Property
     */
    public function setImageFull(string $imageFull): self
    {
        $this->imageFull = $imageFull;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageThumbnail(): string
    {
        return $this->imageThumbnail;
    }

    /**
     * @param string $imageThumbnail
     * @return Property
     */
    public function setImageThumbnail(string $imageThumbnail): self
    {
        $this->imageThumbnail = $imageThumbnail;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumBedrooms(): int
    {
        return $this->numBedrooms;
    }

    /**
     * @param int $numBedrooms
     * @return Property
     */
    public function setNumBedrooms(int $numBedrooms): self
    {
        $this->numBedrooms = $numBedrooms;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumBathrooms(): int
    {
        return $this->numBathrooms;
    }

    /**
     * @param int $numBathrooms
     * @return Property
     */
    public function setNumBathrooms(int $numBathrooms): self
    {
        $this->numBathrooms = $numBathrooms;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return Property
     */
    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Property
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return PropertyType
     */
    public function getPropertyType(): PropertyType
    {
        return $this->propertyType;
    }

    /**
     * @param PropertyType $propertyType
     * @return Property
     */
    public function setPropertyType(PropertyType $propertyType): self
    {
        $this->propertyType = $propertyType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     * @return Property
     */
    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;
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

        $this->updatedAt = $updatedAt;
        return $this;
    }
}

<?php
declare(strict_types = 1);

namespace App\Dto\Response;

/**
 * The response DTO which represents the rating.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Rating
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $businessId;
    /**
     * @var int
     */
    private $value;
    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): Rating
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getBusinessId(): int
    {
        return $this->businessId;
    }

    /**
     * @param int $businessId
     *
     * @return self
     */
    public function setBusinessId(int $businessId): Rating
    {
        $this->businessId = $businessId;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @return self
     */
    public function setValue(int $value): Rating
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): Rating
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

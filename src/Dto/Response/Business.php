<?php
declare(strict_types = 1);

namespace App\Dto\Response;

/**
 * The response DTO which represents the business.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Business
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $constructionYear;
    /**
     * @var int
     */
    private $class;
    /**
     * @var bool
     */
    private $governmental;
    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;
    /**
     * @var \DateTime
     */
    private $updatedAt;

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
    public function setId(int $id): Business
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): Business
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getConstructionYear(): int
    {
        return $this->constructionYear;
    }

    /**
     * @param int $constructionYear
     *
     * @return self
     */
    public function setConstructionYear(int $constructionYear): Business
    {
        $this->constructionYear = $constructionYear;

        return $this;
    }

    /**
     * @return int
     */
    public function getClass(): int
    {
        return $this->class;
    }

    /**
     * @param int $class
     *
     * @return self
     */
    public function setClass(int $class): Business
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGovernmental(): bool
    {
        return $this->governmental;
    }

    /**
     * @param bool $governmental
     *
     * @return self
     */
    public function setGovernmental(bool $governmental): Business
    {
        $this->governmental = $governmental;

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
    public function setCreatedAt(\DateTimeImmutable $createdAt): Business
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(?\DateTime $updatedAt): Business
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

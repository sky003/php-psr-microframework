<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * The business entity which is currently represents the proper database table.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 *
 * @ORM\Entity()
 * @ORM\Table(name="business")
 * @ORM\HasLifecycleCallbacks()
 */
class Business
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", name="construction_year")
     */
    private $constructionYear;
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $class;
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $governmental;
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="business", cascade={"persist", "remove"})
     */
    private $ratings;
    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", name="created_at")
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private $updatedAt;

    /**
     * Business constructor.
     */
    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

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
     * @return \DateTime
     */
    public function getConstructionYear(): \DateTime
    {
        return $this->constructionYear;
    }

    /**
     * @param \DateTime $constructionYear
     *
     * @return self
     */
    public function setConstructionYear(\DateTime $constructionYear): Business
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
     * @return Collection
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    /**
     * @param Collection $ratings
     *
     * @return self
     */
    public function setRatings(Collection $ratings): Business
    {
        $this->ratings = $ratings;

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

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime('now');
    }
}

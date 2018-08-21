<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The rating entity which is currently represents the proper database table.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 *
 * @ORM\Entity()
 * @ORM\Table(name="rating")
 * @ORM\HasLifecycleCallbacks()
 */
class Rating
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
     * @var Business
     *
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="ratings")
     * @ORM\JoinColumn(name="business_id")
     */
    private $business;
    /**
     * The rating value.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $value;
    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", name="created_at")
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
     * @return Business
     */
    public function getBusiness(): Business
    {
        return $this->business;
    }

    /**
     * @param Business $business
     *
     * @return self
     */
    public function setBusiness(Business $business): Rating
    {
        $this->business = $business;

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

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }
}

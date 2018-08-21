<?php
declare(strict_types = 1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * The request DTO which represents the business.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Business implements PropertyChangeTrackerInterface
{
    use PropertyChangeTrackerTrait;

    /**
     * @var int
     *
     * @Assert\IsNull(
     *     groups={"OpCreate"},
     * )
     * @Assert\NotNull(
     *     groups={"OpUpdate"},
     * )
     */
    private $id;
    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     * @Assert\Length(
     *     max=255,
     *     groups={"OpCreate", "OpUpdate"},
     * )
     */
    private $name;
    /**
     * Need better validation here.
     *
     * @var int
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     * @Assert\Expression(
     *     expression="value > minYear and value < maxYear",
     *     values={"minYear": 1, "maxYear": 2018},
     * )
     */
    private $constructionYear;
    /**
     * @var int
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     * @Assert\Range(
     *     min=1,
     *     max=3,
     * )
     */
    private $class;
    /**
     * @var bool
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpUpdate"},
     * )
     */
    private $governmental;

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
}

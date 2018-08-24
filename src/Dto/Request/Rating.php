<?php
declare(strict_types = 1);

namespace App\Dto\Request;

use App\Component\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The request DTO which represents the rating.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class Rating implements PropertyChangeTrackerInterface
{
    use PropertyChangeTrackerTrait;

    /**
     * @var int
     *
     * @Assert\IsNull(
     *     groups={"OpCreate"},
     * )
     */
    private $id;
    /**
     * @var int
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate", "OpCheckRequestAttributes"},
     * )
     * @AppAssert\ExistEntity(
     *     entityClass="App\Entity\Business",
     *     properties={"id"},
     *     groups={"OpCreate", "OpCheckRequestAttributes"},
     * )
     */
    private $businessId;
    /**
     * @var int
     *
     * @Assert\NotBlank(
     *     groups={"OpCreate"},
     * )
     * @Assert\Range(
     *     min=1,
     *     max=5,
     *     groups={"OpCreate"},
     * )
     */
    private $value;

    /**
     * @return int
     */
    public function getId(): ?int
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
}

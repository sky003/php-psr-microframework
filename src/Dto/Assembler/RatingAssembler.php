<?php
declare(strict_types = 1);

namespace App\Dto\Assembler;

use App\Dto\Request;
use App\Dto\Response;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The default implementation of the rating assembler.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class RatingAssembler implements RatingAssemblerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * BusinessAssembler constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(?EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Writes an entity from provided DTO.
     *
     * @param Request\Rating $dto
     *
     * @return Entity\Rating
     */
    public function writeEntity(Request\Rating $dto): Entity\Rating
    {
        if ($dto->getId() !== null) {
            throw new \BadMethodCallException(
                'Unable to write an existent entity because the operation not supported.'
            );
        }

        /** @var Entity\Business $business */
        $business = $this->entityManager->find(Entity\Business::class, $dto->getBusinessId());

        $entity = new Entity\Rating();
        $entity
            ->setBusiness($business)
            ->setValue($dto->getValue());

        return $entity;
    }

    /**
     * Writes DTO from provided entity.
     *
     * @param Entity\Rating $entity
     *
     * @return Response\Rating
     */
    public function writeDto(Entity\Rating $entity): Response\Rating
    {
        $dto = new Response\Rating();
        $dto
            ->setId($entity->getId())
            ->setBusinessId($entity->getBusiness()->getId())
            ->setValue($entity->getValue())
            ->setCreatedAt($entity->getCreatedAt());

        return $dto;
    }
}

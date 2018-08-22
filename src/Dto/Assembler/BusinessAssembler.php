<?php
declare(strict_types = 1);

namespace App\Dto\Assembler;

use App\Dto\Request;
use App\Dto\Response;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The default implementation of the business assembler.
 *
 * This implementation is simple, and it's boring to build it. But it makes sense to
 * follow KISS principle here because it's easy to support, and it works pretty fast.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class BusinessAssembler implements BusinessAssemblerInterface
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
     * Adds missing properties to DTO.
     *
     * This method is really useful to normalize DTO created from PATCH request. It's much easier to
     * validate the normalized DTO.
     *
     * @param Request\Business $dto
     */
    public function normalizeDto(Request\Business $dto): void
    {
        /** @var Entity\Business $entity */
        $entity = $this->entityManager->find(Entity\Business::class, $dto->getId());

        $dto->setTrackerEnabled(false);

        if (!$dto->isPropertyChanged('name')) {
            $dto->setName($entity->getName());
        }
        if (!$dto->isPropertyChanged('constructionYear')) {
            $dto->setConstructionYear((int) $entity->getConstructionYear()->format('Y'));
        }
        if (!$dto->isPropertyChanged('class')) {
            $dto->setClass($entity->getClass());
        }
        if (!$dto->isPropertyChanged('governmental')) {
            $dto->setGovernmental($entity->isGovernmental());
        }

        $dto->setTrackerEnabled(true);
    }

    /**
     * Writes an entity from provided DTO.
     *
     * @param Request\Business $dto
     *
     * @return Entity\Business
     */
    public function writeEntity(Request\Business $dto): Entity\Business
    {
        return $dto->getId() === null ? $this->createEntity($dto) : $this->updateEntity($dto);
    }

    /**
     * Writes DTO from provided entity.
     *
     * @param Entity\Business $entity
     *
     * @return Response\Business
     */
    public function writeDto(Entity\Business $entity): Response\Business
    {
        $dto = new Response\Business();
        $dto
            ->setId($entity->getId())
            ->setName($entity->getName())
            ->setConstructionYear((int) $entity->getConstructionYear()->format('Y'))
            ->setClass($entity->getClass())
            ->setGovernmental($entity->isGovernmental())
            ->setCreatedAt($entity->getCreatedAt())
            ->setUpdatedAt($entity->getUpdatedAt());

        return $dto;
    }

    /**
     * Creates a new entity.
     *
     * @param Request\Business $dto
     *
     * @return Entity\Business
     */
    private function createEntity(Request\Business $dto): Entity\Business
    {
        $entity = new Entity\Business();
        $entity
            ->setName($dto->getName())
            ->setConstructionYear(new \DateTime($dto->getConstructionYear().'-01-01'))
            ->setClass($dto->getClass())
            ->setGovernmental($dto->isGovernmental());

        return $entity;
    }

    /**
     * Update an existent entity.
     *
     * @param Request\Business $dto
     *
     * @return Entity\Business
     */
    private function updateEntity(Request\Business $dto): Entity\Business
    {
        /** @var Entity\Business $entity */
        $entity = $this->entityManager->find(Entity\Business::class, $dto->getId());

        if ($dto->isPropertyChanged('name')) {
            $entity->setName($dto->getName());
        }
        if ($dto->isPropertyChanged('constructionYear')) {
            $entity->setConstructionYear(new \DateTime($dto->getConstructionYear().'-01-01'));
        }
        if ($dto->isPropertyChanged('class')) {
            $entity->setClass($dto->getClass());
        }
        if ($dto->isPropertyChanged('governmental')) {
            $entity->setGovernmental($dto->isGovernmental());
        }

        return $entity;
    }
}

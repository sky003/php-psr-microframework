<?php
declare(strict_types = 1);

namespace App\Service\Business;

use App\Entity\Business;
use App\Entity\Rating;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Default business service implementation.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class BusinessService implements BusinessServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * BusinessService constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     *
     * @return null|Business
     * @throws ServiceException
     */
    public function get(int $id): ?Business
    {
        /** @var Business $entity */
        $entity = $this->entityManager
            ->getRepository(Business::class)
            ->find($id);

        return $entity;
    }

    /**
     * @param Criteria $criteria
     *
     * @return Collection
     * @throws ServiceException
     */
    public function getList(Criteria $criteria): Collection
    {
        $repository = $this->entityManager->getRepository(Business::class);

        if (!$repository instanceof Selectable) {
            throw new \LogicException(
                sprintf('Repository must implement "%s" interface.', Selectable::class)
            );
        }

        return $repository->matching($criteria);
    }

    /**
     * @param Business $entity
     *
     * @throws ServiceException
     */
    public function create(Business $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param Business $entity
     *
     * @throws ServiceException
     */
    public function update(Business $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param Business $entity
     *
     * @throws ServiceException
     */
    public function delete(Business $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * Rates the business.
     *
     * @param Business $businessEntity
     * @param Rating $ratingEntity
     *
     * @throws ServiceException
     */
    public function rateBusiness(Business $businessEntity, Rating $ratingEntity): void
    {
        $businessEntity->getRatings()->add($ratingEntity);

        $this->entityManager->persist($businessEntity);
        $this->entityManager->flush();
    }
}

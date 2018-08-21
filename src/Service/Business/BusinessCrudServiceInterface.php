<?php

declare(strict_types = 1);

namespace App\Service\Business;

use App\Entity\Business;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * Interface to implement the CRUD operations on {@see \App\Entity\Business} entity.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
interface BusinessCrudServiceInterface
{
    /**
     * @param int $id
     *
     * @return null|Business
     * @throws ServiceException
     */
    public function get(int $id): ?Business;

    /**
     * @param Criteria $criteria
     *
     * @return Collection
     * @throws ServiceException
     */
    public function getList(Criteria $criteria): Collection;

    /**
     * @param Business $entity
     *
     * @throws ServiceException
     */
    public function create(Business $entity): void;

    /**
     * @param Business $entity
     *
     * @throws ServiceException
     */
    public function update(Business $entity): void;

    /**
     * @param Business $entity
     *
     * @throws ServiceException
     */
    public function delete(Business $entity): void;
}

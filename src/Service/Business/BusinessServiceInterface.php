<?php
declare(strict_types = 1);

namespace App\Service\Business;

use App\Entity\Business;
use App\Entity\Rating;

/**
 * Interface to implement the business service.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
interface BusinessServiceInterface extends BusinessCrudServiceInterface
{
    /**
     * Rates the business.
     *
     * @param Business $businessEntity
     * @param Rating $ratingEntity
     *
     * @throws ServiceException
     */
    public function rateBusiness(Business $businessEntity, Rating $ratingEntity): void;
}

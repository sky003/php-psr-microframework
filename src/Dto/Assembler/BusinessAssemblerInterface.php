<?php
declare(strict_types = 1);

namespace App\Dto\Assembler;

use App\Dto\Request;
use App\Dto\Response;
use App\Entity;

/**
 * Interface to implement the business assembler.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
interface BusinessAssemblerInterface
{
    /**
     * Adds missing properties to DTO.
     *
     * This method is really useful to normalize DTO created from PATCH request. It's much easier to
     * validate the normalized DTO.
     *
     * @param Request\Business $dto
     */
    public function normalizeDto(Request\Business $dto): void;

    /**
     * Writes an entity from provided DTO.
     *
     * @param Request\Business $dto
     *
     * @return Entity\Business
     */
    public function writeEntity(Request\Business $dto): Entity\Business;

    /**
     * Writes DTO from provided entity.
     *
     * @param Entity\Business $entity
     *
     * @return Response\Business
     */
    public function writeDto(Entity\Business $entity): Response\Business;
}

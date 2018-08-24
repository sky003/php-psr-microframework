<?php
declare(strict_types = 1);

namespace App\Dto\Assembler;

use App\Dto\Request;
use App\Dto\Response;
use App\Entity;

/**
 * Interface to implement the rating assembler.
 *
 * In case full CRUD implementation for rating will be required, feel free to extend this interface.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
interface RatingAssemblerInterface
{
    /**
     * Writes an entity from provided DTO.
     *
     * @param Request\Rating $dto
     *
     * @return Entity\Rating
     */
    public function writeEntity(Request\Rating $dto): Entity\Rating;

    /**
     * Writes DTO from provided entity.
     *
     * @param Entity\Rating $entity
     *
     * @return Response\Rating
     */
    public function writeDto(Entity\Rating $entity): Response\Rating;
}

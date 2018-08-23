<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Dto\Assembler\BusinessAssemblerInterface;
use App\Dto\Request;
use App\Entity\Business;
use App\Service\Business\BusinessServiceInterface;
use App\Service\Business\ServiceException;
use Doctrine\Common\Collections\Criteria;
use Middlewares\Utils\HttpErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zend\Diactoros\Response;

/**
 * Class BusinessController.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class BusinessController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var BusinessAssemblerInterface
     */
    private $businessAssembler;
    /**
     * @var BusinessServiceInterface
     */
    private $businessService;

    /**
     * BusinessController constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param BusinessAssemblerInterface $businessAssembler
     * @param BusinessServiceInterface $businessService
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        BusinessAssemblerInterface $businessAssembler,
        BusinessServiceInterface $businessService
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->businessAssembler = $businessAssembler;
        $this->businessService = $businessService;
    }

    /**
     * Creates the business.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws HttpErrorException
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Request\Business $requestDto */
        $requestDto = $this->serializer->deserialize(
            $request->getBody()->getContents(),
            Request\Business::class,
            'json'
        );

        $errors = $this->validator->validate($requestDto, null, ['OpCreate']);
        if (\count($errors) > 0) {
            throw HttpErrorException::create(422, ['validationErrors' => $errors]);
        }

        $entity = $this->businessAssembler->writeEntity($requestDto);

        try {
            $this->businessService->create($entity);
        } catch (ServiceException $e) {
            throw HttpErrorException::create(500, [], $e);
        }

        $responseDto = $this->businessAssembler->writeDto($entity);

        $response = new Response();
        $response->getBody()->write(
            $this->serializer->serialize($responseDto, 'json')
        );

        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * Update the business.
     *
     * This method can handle PUT request as well as PATCH request.
     *
     * @param ServerRequestInterface $request
     * @param array $args The router variables parsed from the request path.
     *
     * @return ResponseInterface
     * @throws HttpErrorException
     */
    public function update(ServerRequestInterface $request, array $args): ResponseInterface
    {
        /** @var Request\Business $requestDto */
        $requestDto = $this->serializer->deserialize(
            $request->getBody()->getContents(),
            Request\Business::class,
            'json'
        );

        $requestDto->setId((int) $args['id']);

        // Validate all input required to normalize provided DTO.
        // DTO can be safely normalized in case validation has passed.
        $errors = $this->validator->validate($requestDto, null, ['OpNormalize']);
        if (\count($errors) > 0) {
            // That means that the business can not be found (identifier is empty, or not valid)
            // so DTO can not be normalized.
            throw HttpErrorException::create(404);
        }

        $this->businessAssembler->normalizeDto($requestDto);

        // Validate all DTO.
        $errors = $this->validator->validate($requestDto, null, ['OpUpdate']);
        if (\count($errors) > 0) {
            throw HttpErrorException::create(422, ['validationErrors' => $errors]);
        }

        $entity = $this->businessAssembler->writeEntity($requestDto);

        try {
            $this->businessService->update($entity);
        } catch (ServiceException $e) {
            throw HttpErrorException::create(500, [], $e);
        }

        $responseDto = $this->businessAssembler->writeDto($entity);

        $response = new Response();
        $response->getBody()->write(
            $this->serializer->serialize($responseDto, 'json')
        );

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * Returns the list of businesses.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws HttpErrorException
     */
    public function getList(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $offset = $queryParams['offset'] ?? 0;
        $limit = $queryParams['limit'] ?? 25;
        $searchQuery = $queryParams['q'] ?? null;

        $criteria = Criteria::create()
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        if (!empty($searchQuery)) {
            // This search is pretty dumb.
            $criteria->where(Criteria::expr()->contains('name', $searchQuery));
        }

        try {
            $collection = $this->businessService->getList($criteria);
        } catch (ServiceException $e) {
            throw HttpErrorException::create(500, [], $e);
        }

        $dtoCollection = $collection->map(function (Business $business) {
            return $this->businessAssembler->writeDto($business);
        });

        $response = new Response();
        $response->getBody()->write(
            $this->serializer->serialize($dtoCollection, 'json')
        );

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}

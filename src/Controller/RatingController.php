<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Dto\Assembler\RatingAssemblerInterface;
use App\Dto\Request;
use App\Service\Business\BusinessServiceInterface;
use App\Service\Business\ServiceException;
use Middlewares\Utils\HttpErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zend\Diactoros\Response;

/**
 * The rating controller.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class RatingController
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
     * @var RatingAssemblerInterface
     */
    private $ratingAssembler;
    /**
     * @var BusinessServiceInterface
     */
    private $businessService;

    /**
     * BusinessController constructor.
     *
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param RatingAssemblerInterface $ratingAssembler
     * @param BusinessServiceInterface $businessService Currently this service can be used here, but in future it need
     * to be replaced by the proper rating service.
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        RatingAssemblerInterface $ratingAssembler,
        BusinessServiceInterface $businessService
    ) {
        $this->serializer      = $serializer;
        $this->validator       = $validator;
        $this->ratingAssembler = $ratingAssembler;
        $this->businessService = $businessService;
    }

    /**
     * Rates the business.
     *
     * @param ServerRequestInterface $request
     * @param array $args
     *
     * @return ResponseInterface
     * @throws HttpErrorException
     */
    public function create(ServerRequestInterface $request, array $args): ResponseInterface
    {
        /** @var Request\Rating $requestDto */
        $requestDto = $this->serializer->deserialize(
            $request->getBody()->getContents(),
            Request\Rating::class,
            'json'
        );

        // Set the request attribute parsed from the request path.
        $requestDto->setBusinessId((int) $args['business_id']);

        // Check only this request attribute, if it's actually exists.
        $errors = $this->validator->validate($requestDto, null, ['OpCheckRequestAttributes']);
        if (\count($errors) > 0) {
            throw HttpErrorException::create(404);
        }

        $errors = $this->validator->validate($requestDto, null, ['OpCreate']);
        if (\count($errors) > 0) {
            throw HttpErrorException::create(422, ['validationErrors' => $errors]);
        }

        $entity = $this->ratingAssembler->writeEntity($requestDto);

        try {
            $this->businessService->rateBusiness($entity->getBusiness(), $entity);
        } catch (ServiceException $e) {
            throw HttpErrorException::create(500, [], $e);
        }

        $responseDto = $this->ratingAssembler->writeDto($entity);

        $response = new Response();
        $response->getBody()->write(
            $this->serializer->serialize($responseDto, 'json')
        );

        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
}

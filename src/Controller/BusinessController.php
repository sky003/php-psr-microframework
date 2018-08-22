<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Dto\Assembler\BusinessAssemblerInterface;
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
}

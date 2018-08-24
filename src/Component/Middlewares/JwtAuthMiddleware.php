<?php
declare(strict_types = 1);

namespace App\Component\Middlewares;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Middlewares\Utils\HttpErrorException;
use Middlewares\Utils\Traits\HasResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Simple JWT authentication middleware.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class JwtAuthMiddleware implements MiddlewareInterface
{
    use HasResponseFactory;

    /**
     * @var Signer
     */
    private $signer;
    /**
     * @var Signer\Key
     */
    private $key;

    /**
     * JwtAuthMiddleware constructor.
     *
     * @param Signer $signer
     * @param Signer\Key $key
     */
    public function __construct(Signer $signer, Signer\Key $key)
    {
        $this->signer = $signer;
        $this->key = $key;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws HttpErrorException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$request->hasHeader('Authorization')) {
            return $this->createResponse(401);
        }

        $rawToken = $this->extractCredentialsFromAuthHeaderValue(
            $request->getHeader('Authorization')[0]
        );
        $token = (new Parser())->parse($rawToken);

        if (!$token->verify($this->signer, $this->key)) {
            return $this->createResponse(401);
        }

        return $handler->handle($request);
    }

    /**
     * Extracts JWT token from the request header value.
     *
     * @param string $value
     *
     * @return null|string
     */
    private function extractCredentialsFromAuthHeaderValue(string $value): ?string
    {
        $headerValueParts = \explode(' ', $value);

        if (\count($headerValueParts) !== 2 && $headerValueParts[0] !== 'Bearer') {
            return null;
        }

        return $headerValueParts[1];
    }
}

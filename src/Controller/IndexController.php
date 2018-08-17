<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * The default controller.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class IndexController
{
    /**
     * The default action.
     *
     * Returns an empty JSON object to client.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function getIndex(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('{}');

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}

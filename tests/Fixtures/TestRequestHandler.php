<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route\Tests\Fixtures;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Request\ArraySerializer;
use Zend\Diactoros\Response\JsonResponse;

class TestRequestHandler implements RequestHandlerInterface {
    private $name;

    public function __construct(string $name) {
        $this->name = $name;
    }


    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface {
        $response = new JsonResponse([
            'handler' => $this->name,
            'request' => ArraySerializer::toArray($request),
        ]);

        return $response;
    }
}

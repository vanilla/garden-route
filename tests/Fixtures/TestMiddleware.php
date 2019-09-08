<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route\Tests\Fixtures;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * A middleware that adds a value to the header of its request and response.
 */
class TestMiddleware implements MiddlewareInterface {
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $request = $request->withAddedHeader('request', $this->getName());
        $response = $handler->handle($request);
        $response = $response->withAddedHeader('response', $this->getName());

        return $response;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }
}

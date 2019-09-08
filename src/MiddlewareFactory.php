<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;


use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class MiddlewareFactory implements MiddlewareFactoryInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Create the middleware with the given name.
     *
     * @param string $id The ID of the middleware to create.
     * @return MiddlewareInterface Returns a middleware.
     */
    public function getMiddleware(string $id): MiddlewareInterface {
        $r = $this->container->get($id);
    }
}

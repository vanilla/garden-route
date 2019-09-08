<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareFactoryInterface {
    /**
     * Create the middleware with the given name.
     *
     * @param string $id The ID of the middleware to create. This is usually the class name.
     * @return MiddlewareInterface Returns a middleware.
     */
    public function getMiddleware(string $id): MiddlewareInterface;
}

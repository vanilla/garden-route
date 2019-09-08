<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;

trait MiddlewareAwareTrait {
    /**
     * @var string[]
     */
    private $middlewares = [];

    /**
     * Add a middleware to the stack.
     *
     * @param string $middleware The middleware class.
     */
    public function addMiddleware(string $middleware) {
        $this->middlewares[] = $middleware;
    }
}

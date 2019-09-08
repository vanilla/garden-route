<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;

final class RouteCollection {
    use MiddlewareAwareTrait;

    public function map(string $name, string $method, string $path, $handler): Route {
        throw new NotImplementedException(__METHOD__);
    }

    public function get(string $name, string $path, $handler): Route {
        throw new NotImplementedException(__METHOD__);
    }

    public function post(string $name, string $path, $handler): Route {
        throw new NotImplementedException(__METHOD__);
    }

    public function group(string $prefix, callable $loader): RouteCollection {
        throw new NotImplementedException(__METHOD__);
    }
}

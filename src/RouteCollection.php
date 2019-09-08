<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;

final class RouteCollection {
    use MiddlewareAwareTrait;

    public function map(string $method, string $path, $handler): Route {
        throw new NotImplementedException(__METHOD__);
    }

    public function get(string $path, $handler): Route {
        return $this->map('GET', $path, $handler);
    }

    public function post(string $path, $handler): Route {
        return $this->map('POST', $path, $handler);
    }

    public function put(string $path, $handler): Route {
        return $this->map('PUT', $path, $handler);
    }

    public function patch(string $path, $handler): Route {
        return $this->map('PATCH', $path, $handler);
    }

    public function delete(string $path, $handler): Route {
        return $this->map('DELETE', $path, $handler);
    }

    public function head(string $path, $handler): Route {
        return $this->map('HEAD', $path, $handler);
    }

    public function options(string $path, $handler): Route {
        return $this->map('OPTIONS', $path, $handler);
    }

    public function group(string $prefix, callable $loader): RouteCollection {
        throw new NotImplementedException(__METHOD__);
    }
}

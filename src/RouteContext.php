<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Represents a route that was matched to a request.
 *
 * When a route is set as an attribute of the request it matched to, along with expanded arguments from the path.
 */
final class RouteContext {
    /**
     * @var Route
     */
    private $route;

    /**
     * @var array
     */
    private $args;

    /**
     * RouteContext constructor.
     *
     * @param Route $route
     * @param array $args
     */
    public function __construct(Route $route, array $args) {
        $this->route = $route;
        $this->args = $args;
    }

    /**
     * Get the route that was assigned to a request.
     *
     * This is a convenience method to help with type casting.
     *
     * @param ServerRequestInterface $request The request to inspect.
     * @return RouteContext Returns the context that was set on the request.
     */
    public static function fromRequest(ServerRequestInterface $request): RouteContext {
        $route = $request->getAttribute(self::class, null);
        return $route;
    }

    /**
     * Set this context on a request.
     *
     * @param ServerRequestInterface $request The request to set.
     * @return ServerRequestInterface Returns a new request with the set route.
     */
    public function toRequest(ServerRequestInterface $request): ServerRequestInterface {
        $r = $request->withAttribute(self::class, $this);
        return $r;
    }

    /**
     * @return Route
     */
    public function getRoute(): Route {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getArgs(): array {
        return $this->args;
    }
}

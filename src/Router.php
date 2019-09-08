<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The main routing component.
 *
 * Right now this is a request handler, but it could be a middleware with the dispatcher passed as the argument to process instead
 * of being a property here. Hmm...
 */
final class Router implements RequestHandlerInterface {
    /**
     * @var RequestHandlerInterface
     */
    private $dispatcher;

    /**
     * @var RequestHandlerInterface
     */
    private $notFoundHandler;

    /**
     * @var RequestHandlerInterface
     */
    private $methodNotAllowedHandler;

    /**
     * @var RouteLoader
     */
    private $loader;

    /**
     * @var MiddlewareFactoryInterface
     */
    private $middlewareFactory;

    /**
     * @var string
     */
    private $cacheDir;

    public function __construct(
        RouteLoaderInterface $loader,
        MiddlewareFactoryInterface $middlewareFactory,
        string $cacheDir = ''
    ) {

        $this->loader = $loader;
        $this->middlewareFactory = $middlewareFactory;
        $this->cacheDir = $cacheDir;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * Get the handler used to dispatch matched routes.
     *
     * @return RequestHandlerInterface
     */
    public function getDispatcher(): RequestHandlerInterface {
        return $this->dispatcher;
    }

    /**
     * Set the handler used to dispatch matched routes.
     *
     * @param RequestHandlerInterface $dispatcher
     */
    public function setDispatcher(RequestHandlerInterface $dispatcher): void {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get the handler used to dispatch unmatched requests.
     *
     * @return RequestHandlerInterface
     */
    public function getNotFoundHandler(): RequestHandlerInterface {
        return $this->notFoundHandler;
    }

    /**
     * Set the handler used to dispatch unmatched requests.
     *
     * @param RequestHandlerInterface $notFoundHandler
     */
    public function setNotFoundHandler(RequestHandlerInterface $notFoundHandler): void {
        $this->notFoundHandler = $notFoundHandler;
    }

    /**
     * Get the handler used to match method not allowed errors.
     *
     * @return RequestHandlerInterface
     */
    public function getMethodNotAllowedHandler(): RequestHandlerInterface {
        return $this->methodNotAllowedHandler;
    }

    /**
     * Set the handler used to match method not allowed errors.
     *
     * @param RequestHandlerInterface $methodNotAllowedHandler
     */
    public function setMethodNotAllowedHandler(RequestHandlerInterface $methodNotAllowedHandler): void {
        $this->methodNotAllowedHandler = $methodNotAllowedHandler;
    }
}

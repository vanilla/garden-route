<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route\Tests;

use Garden\Route\MiddlewareFactory;
use Garden\Route\RouteCollection;
use Garden\Route\RouteContext;
use Garden\Route\RouteLoaderInterface;
use Garden\Route\Router;
use Garden\Route\Tests\Fixtures\ArrayContainer;
use PHPUnit\Framework\TestCase;
use Pimple\Psr11\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\ServerRequest;

/**
 * Some basic tests from the readme.
 */
class ReadmeTest extends TestCase {
    public function setUp() {
        parent::setUp();
    }

    /**
     * A basic ping test to see if our message comes back to us in the response.
     */
    public function testPing() {
        $router = $this->getMicroRouter();

        $request = new ServerRequest([], [], 'http://example.com?m=foo', 'GET');
        $response = $router->handle($request);

        $this->assertSame('foo', $response->getBody());
    }

    /**
     * Get a test route loader.
     *
     * @return RouteLoaderInterface
     */
    protected function getMicroLoader(): RouteLoaderInterface {
        return new class implements RouteLoaderInterface {
            public function loadRoutes(RouteCollection $routes): void {
                $routes->get('/ping', function (ServerRequestInterface $request): ResponseInterface {
                    return new TextResponse($request->getQueryParams()['m'] ?? 'Hi');
                });

                $routes->post('/pong', function (ServerRequestInterface $request): ResponseInterface {
                    return new JsonResponse([
                        'headers' => $request->getHeaders(),
                        'body' => $request->getParsedBody(),
                    ]);
                });
            }
        };
    }

    /**
     * Get a closure dispatcher.
     *
     * @return RequestHandlerInterface
     */
    protected function getClosureDispatcher(): RequestHandlerInterface {
        return new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface {
                $match = RouteContext::fromRequest($request);

                $callback = $match->getRoute()->getHandler();
                $response = $callback($request);

                return $response;
            }
        };
    }

    /**
     * Create a test router.
     *
     * @return Router
     */
    protected function getMicroRouter(): Router {
        $router = new Router(
            $this->getMicroLoader(),
            new MiddlewareFactory(new \Garden\Container\Container())
        );

        $router->setDispatcher($this->getClosureDispatcher());
        return $router;
    }
}

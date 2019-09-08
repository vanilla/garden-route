# Garden Route

***This is pre-alpha software. You can't even use this in its current state.***

*A routing library that is heavily PSR focused.*

## Introduction

This is a small routing library built on top of [Symfony Routing](https://symfony.com/doc/current/components/routing.html) with [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware support. This library is heavily inspired by [The PHP League's route package](https://route.thephpleague.com) and the [Slim Framework](http://slim-website.lgse.com/docs/v4/).

## Goals

Here are the main goals of this package:

- Wrap an existing routing package with a friendly, controlled interface so that we have the option of replacing the underlying route implementation in the future if we wish.
- Add middleware support at the global, group, and route scopes.
- Provide support for larger routing scenarios and not just microframeworks. Caching is a must. Some sacrifices in the API are considered acceptable to support caching.
- Allow for flexible route definition without forcing a specific methodology.
- Code to as many PSR interfaces as possible. Specifically, code to the `RequestHandlerInterface` and `MiddlewareInterface` as much as possible.
- Conversely, this package aims to **NOT** implement the actual PSR interfaces, except for testing.
- This package is not a framework and should never become one.
- Support for URL generation via reverse routing. This is something that most routing libraries specifically don't do and application developers often find themselves hitting a brick wall once they realize the need this functionality.

## The Router Class

The main class for executing routes is the `Router` class. A basic application might look like this:

```php
use Garden\Route\Router;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

$routeLoader = ??? // Figure out how you want to load routes.
$middlewareFactory = ??? // Create a container for known middleware.

$router = new Router($routeLoader, $middlewareFactory, __DIR__.'/cache');

$request = ServerRequestFactory::fromGlobals();
$response = $router->handle($request);

$emitter = new SapiEmitter();
$emitter->emit($response);
``` 

## The RouteLoaderInterface

The `RouteLoaderInterface` is how you define actual routes in your application. Route loading is abstracted into a method on an interface for the following reasons:

1. For caching to be supported, routes need to be loaded in some sort of callback that is only called the first time routes are required. The interface supports this quite nicely.
2. A simple interface allows for some very complex route generation scenarios. Want a microframework? Just define all your routes inline. Maybe you have a large app that scans its directory structure for classes that implement `RouteLoaderInterface`. That's supported too. You could make loaders that work on static files, class annotations, or most any other possibility.

### Example Route Loader

Here is a basic route loader for a hypothetical microframework.

```php
class Micro implements RouteLoaderInterface {
    public function loadRoutes(RouteCollection $routes): void {
        $routes->get('/ping', function (RequestInterface $request) {
            return new TextResponse('Hello World!');
        });
        
        $routes->post('/pong', function (RequestInterface $request) {
            return new JsonRespone(['message' => 'Hello World!']);
        });
    }
}
```

## Writing the Dispatcher

You notice in the above example, closures are passed as the last argument to the route definitions. When defining routes, the handler can be whatever you decide it to be. It's up to you to write a `RequestHandler` to transform a matched route into a response. In this case we call the request handler the **dispatcher**. It is meant to dispatch matched routes.

### A Microframework Dispatcher

Let's write a basic dispatcher that works on the above microframework routes.

```php
class ClosureDispatcher implements RequestHandlerInterface {
    public function handle(ServerRequestInterface $request): ResponseInterface {
        $match = RouteContext::fromRequest($request);
        
        $callback = $match->getRoute()->getHandler();
        $response = $callback($request);
        
        return $response;
    }
}
```

Aside from some sensible error checking that's it!

When the router matches a route it adds it as an attribute to the request and then passes it off to the dispatcher. In your dispatcher, you can use the `RouteContext::fromRequest()` convenience method to get all the information from the match which will include the route, its name, and any arguments that were expanded fom the path.

### A Controller Dispatcher

Let's consider a more complex example that wants to dispatch to methods on controller classes. First, let's see how those routes may be defined:

```php
class ApplciationRoutes implements RouteLoaderInterface {
    public function loadRoutes(RouteCollection $routes): void {
        $routes->get('/users', 'UsersController:listUsers');
        $routes->get('/users/{id}', 'UsersController:getUser');
        $routes->post('/users', 'UsersController:addUser');
    }
}
```

In the above example, we decide to specify controllers and methods as a simple string where the method is separated with a colon. We can write a dispatcher for that like so:

```php
class ControllerDispatcher implements RequestHandlerInterface {
    public function __construct(ContainerInterface $controllers) {
        $this->controllers = $controllers;
    }
    
    public function handle(ServerRequestInterface $request): ResponseInterface {
        $match = RouteContext::fromRequest($request);
    
        list($controllerName, $method) = $match->getRoute()->getHandler();
        $controller = $this->controllers->get($controllerName);
        
        $response = call_user_func_array(
            [$controller, $method],
            array_merge([$request], $match->getArgs())
        );
        return $response;
    }
}
```

The dispatcher above has a container dependency that acts as a factory for controllers. Any matched route arguments are passed off to the controller method in a very basic way. In a real world app you would probably use reflection to do more sophisticated argument mapping.

Also note that your individual methods don't have to return a `ResponseInterface`. As long as your dispatcher knows how to turn the method's return into a response then it can be anything. Maybe you just want to return a basic PHP array and have the dispatcher turn it into a JSON response. Maybe the dispatcher constructs a specific response depending on the content type. Maybe it passes the return off to a templating engine. Where you create your response is up to you.

## Implementation Plan

Currently, this library has no implementation. There are just a bunch of interfaces and skeleton classes. A basic plan for filling out this class might be:

1. First of all, none of the architecture should be considered final. Maybe we'll discover some real difficulties during implementation. Now is the time to be flexible. I recommend we use `TODO` comments to keep track of stuff we aren't so sure about. That way another set of eyes may offer a different perspective. Also looking at the libraries that inspired this one will provide some great ideas.

2. As an experiment, I would like to implement this library using **test driven development**. That means you write tests before you develop. The tests will initially fail and then you do the implementation by fixing the tests. There will be a lot of small unit tests. There will also be quite a few larger functional and integration tests. The larger tests should have a *real world* feel to them in order to tell us if the library has friendly interface for actual development scenarios. Real looking tests will also expose different use-cases.

3. As a corollary to 1 and 2 above: Initial development will involve a lot of **refactoring**. An IDE with proper refactoring tools is recommended.

<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;

interface RouteLoaderInterface {
    /**
     * Load routes into a given route collection.
     *
     * @param RouteCollection $routes The destination for the routes.
     */
    public function loadRoutes(RouteCollection $routes): void;
}

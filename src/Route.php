<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;

final class Route implements RouteInterface {
    use MiddlewareAwareTrait;

    public function getHandler(): string {
        throw new NotImplementedException(__METHOD__);
    }
}

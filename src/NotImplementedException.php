<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2019 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Route;


use PHPUnit\Framework\MockObject\BadMethodCallException;

class NotImplementedException extends BadMethodCallException {
    public function __construct(string $method = "") {
        parent::__construct("$method not implemented.", 500);
    }
}

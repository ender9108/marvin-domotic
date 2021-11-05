<?php
namespace App\Services\Wifi\Exceptions;

use RuntimeException;

/**
 * Class UnknownSystem.
 */
class UnknownSystemException extends RuntimeException
{
    /**
     * UnknownSystem constructor.
     */
    public function __construct()
    {
        parent::__construct("Operation system doesn't support: ".PHP_OS);
    }
}

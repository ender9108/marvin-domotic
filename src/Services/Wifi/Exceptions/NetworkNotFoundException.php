<?php
namespace App\Services\Wifi\Exceptions;

use RuntimeException;

/**
 * Class NetworkNotFoundException.
 */
class NetworkNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Unknown network, try to find another one');
    }
}

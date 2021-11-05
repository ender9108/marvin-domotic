<?php
namespace App\Services\Wifi;

use App\Services\Wifi\System\Collection;
use App\Services\Wifi\System\Command;
use App\Services\Wifi\System\Networks;

/**
 * Class Wifi.
 */
class Wifi
{
    /**
     * @return Collection
     * @throws \Exception
     */
    public static function scan(): Collection
    {
        return (new Networks(new Command()))->scan();
    }
}

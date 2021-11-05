<?php

namespace App\Services\Wifi\System;

use App\Services\Wifi\Exceptions\NetworkNotFoundException;
use Tightenco\Collect\Support\Collection as BaseCollection;

/**
 * Class Collection.
 */
class Collection extends BaseCollection
{
    /**
     * @return array
     */
    public function getAll()
    {
        return $this->all();
    }

    /**
     * @param string $ssid
     *
     * @return AbstractNetwork
     */
    public function getBySsid(string $ssid)
    {
        return $this->where('ssid', $ssid)->firstOrFail();
    }

    /**
     * @param string $bssid
     *
     * @return AbstractNetwork
     */
    public function getByBssid(string $bssid)
    {
        return $this->where('bssid', $bssid)->firstOrFail();
    }

    /**
     * @return AbstractNetwork[]
     */
    public function getConnected()
    {
        return $this->where('connected', true)->all();
    }

    /**
     * @throws NetworkNotFoundException
     *
     * @return AbstractNetwork
     */
    public function firstOrFail()
    {
        if ($this->isEmpty()) {
            throw new NetworkNotFoundException();
        }

        return $this->first();
    }
}

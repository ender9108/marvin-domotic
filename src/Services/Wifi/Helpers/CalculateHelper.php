<?php
namespace App\Services\Wifi\Helpers;

class CalculateHelper
{
    public static function toDbm(int $quality): float
    {
        return ($quality / 2) - 100;
    }

    public static function toQuality(int $dbm): float
    {
        return 2 * ($dbm + 100);
    }
}
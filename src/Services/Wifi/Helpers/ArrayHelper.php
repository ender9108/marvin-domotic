<?php
namespace App\Services\Wifi\Helpers;

class ArrayHelper
{
    public static function trimFirst(array $array): array
    {
        array_walk($array, function (&$item) {
            list($firstElement) = $item;

            $item = trim($firstElement);
        });

        return $array;
    }
}
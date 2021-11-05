<?php
namespace App\Services\Wifi\Helpers;

class StringHelper
{
    /**
     * Extracting bssid from passed string.
     *
     * @param string $string
     * @param int    $matchColumn
     *
     * @return array
     */
    public static function extractBssid(string $string, int $matchColumn): array
    {
        $extractedBssid = [];

        preg_match_all(
            '/(\w{2}:\w{2}:\w{2}:\w{2}:\w{2}:\w{2})/',
            $string,
            $extractedBssid
        );

        return array_unique(array_column($extractedBssid, $matchColumn));
    }

    /**
     * Converting string to hex.
     *
     * @param string $string
     *
     * @return string
     */
    public static function toHex(string $string): string
    {
        $len = strlen($string);
        $hex = '';

        for ($i = 0; $i < $len; $i++) {
            $hex .= substr('0'.dechex(ord($string[$i])), -2);
        }

        return strtoupper($hex);
    }

    /**
     * Glue commands.
     *
     * @param string ...$commands
     *
     * @return string
     */
    public static function glueCommands(string ...$commands): string
    {
        return implode(' && ', $commands);
    }

    /**
     * Get string content after.
     *
     * @param string $string
     * @param string $char
     *
     * @return string
     */
    public static function extractAfter(string $string, string $char = ':'): string
    {
        $title = strtok($string, $char) ?: '';
        $value = substr($string, strlen($title));

        return trim(ltrim($value, $char));
    }
}
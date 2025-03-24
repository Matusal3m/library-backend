<?php
namespace App\Util;

class Converter
{
    public static function convertKeysToBoolean(array $data, array $keys): array
    {
        array_walk($data, function (&$element) use ($keys) {
            foreach ($keys as $key) {
                if (isset($element[$key])) {
                    $element[$key] = (bool) $element[$key];
                }
            }
        });
        return $data;
    }
}

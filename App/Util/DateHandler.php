<?php
namespace App\Util;

class DateHandler
{
    private static $format = 'd-m-Y H:i';

    public static function dateNow(): string
    {
        return date_create('now')->format(self::$format);
    }

    public static function addDaysToDate(string $date, int $days): string
    {
        $dateTime     = date_create_from_format(self::$format, $date);
        $daysInterval = date_interval_create_from_date_string($days . 'days');

        $dateTime->add($daysInterval);

        return $dateTime->format(self::$format);
    }

    public static function daysFromNow(int $days): string
    {
        $dateTime     = date_create('now');
        $daysInterval = date_interval_create_from_date_string($days . 'days');

        $dateTime->add($daysInterval);

        return $dateTime->format(self::$format);
    }
}

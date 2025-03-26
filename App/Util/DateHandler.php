<?php
namespace App\Util;

class DateHandler
{
    private static $format = 'd-m-Y H:i';

    public static function dateNow(): int
    {
        return date_create('now')->getTimestamp();
    }

    public static function addDaysToDate(int $date, int $days): int
    {
        $dateTime     = date_create_from_format(self::$format, $date);
        $daysInterval = date_interval_create_from_date_string($days . 'days');

        $dateTime->add($daysInterval);

        return $dateTime->getTimestamp();
    }

    public static function daysFromNow(int $days): int
    {
        $dateTime     = date_create('now');
        $daysInterval = date_interval_create_from_date_string($days . 'days');

        $dateTime->add($daysInterval);

        return $dateTime->getTimestamp();
    }

    public static function isGreater(int $dateOne, int $dateTwo): bool
    {
        return $dateOne > $dateTwo;
    }

    public static function timestampToReadable(int $timestamp): string
    {
        return date(self::$format, $timestamp);
    }
}

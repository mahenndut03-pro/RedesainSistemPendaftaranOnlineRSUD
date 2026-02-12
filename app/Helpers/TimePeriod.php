<?php
namespace App\Helpers;

use DateTime;

class TimePeriod
{
    public const PERIOD_PAGI = 'PAGI';
    public const PERIOD_SIANG = 'SIANG';
    public const PERIOD_SORE = 'SORE';
    public const PERIOD_LUAR = 'LUAR_JAM';

    protected static $ranges = [
        self::PERIOD_PAGI => ['07:30', '08:30'],
        self::PERIOD_SIANG => ['12:30', '14:00'],
        self::PERIOD_SORE => ['15:00', '17:00'],
    ];

    public static function current($time = null): string
    {
        if ($time instanceof DateTime) {
            $dt = $time;
        } elseif (is_string($time)) {
            $dt = new DateTime($time);
        } elseif (is_null($time)) {
            $dt = new DateTime('now');
        } else {
            throw new \InvalidArgumentException('Invalid $time');
        }

        $minutes = intval($dt->format('H')) * 60 + intval($dt->format('i'));

        foreach (self::$ranges as $period => [$start, $end]) {
            [$sh, $sm] = explode(':', $start);
            [$eh, $em] = explode(':', $end);
            $startMinutes = intval($sh) * 60 + intval($sm);
            $endMinutes = intval($eh) * 60 + intval($em);

            if ($minutes >= $startMinutes && $minutes <= $endMinutes) {
                return $period;
            }
        }

        return self::PERIOD_LUAR;
    }

    public static function label($time = null): string
    {
        $p = self::current($time);

        switch ($p) {
            case self::PERIOD_PAGI:
                return 'Jam Pelayanan Poliklinik PAGI : 07.30 – 08.30 WIB';
            case self::PERIOD_SIANG:
                return 'Jam Pelayanan Poliklinik SIANG : 12.30 – 14.00 WIB';
            case self::PERIOD_SORE:
                return 'Jam Pelayanan Poliklinik SORE : 15.00 – 17.00 WIB';
            default:
                return 'Di luar jam pelayanan poliklinik';
        }
    }
}

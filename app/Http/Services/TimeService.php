<?php

namespace App\Http\Services;

class TimeService
{
    public static function secondsToHoursAndMinutes($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        return $hours . 'h ' . $minutes . 'm';
    }

    public static function secondsToHours($seconds) {
        $hours = floor($seconds / 3600);
        return $hours;
    }
}

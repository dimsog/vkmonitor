<?php

declare(strict_types=1);

namespace App\Services\Calendar;

class WeekNames
{
    public static function getNameByWeekId($weekId): string
    {
        return [
            'понедельник',
            'вторник',
            'среда',
            'четверг',
            'пятница',
            'суббота',
            'воскресенье'
        ][$weekId - 1];
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Calendar;

use App\Services\Calendar\WeekNames;
use Tests\TestCase;

class WeekNamesTest extends TestCase
{
    public function testGetNameByWeekId()
    {
        $this->assertEquals('понедельник', WeekNames::getNameByWeekId(1));
        $this->assertEquals('вторник', WeekNames::getNameByWeekId(2));
        $this->assertEquals('среда', WeekNames::getNameByWeekId(3));
        $this->assertEquals('четверг', WeekNames::getNameByWeekId(4));
        $this->assertEquals('пятница', WeekNames::getNameByWeekId(5));
        $this->assertEquals('суббота', WeekNames::getNameByWeekId(6));
        $this->assertEquals('воскресенье', WeekNames::getNameByWeekId(7));
    }
}

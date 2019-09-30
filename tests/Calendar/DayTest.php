<?php

namespace Tests\Calendar;

use Calendar\Day;
use Carbon\CarbonImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class DayTest extends TestCase
{
    public function testHasATimezone()
    {
        $day = new Day(2019, 9, 27, new DateTimeZone('America/Chicago'));
        $this->assertSame('America/Chicago', $day->timezoneName);
    }

    public function testForwardsPropertyGetsToCarbon()
    {
        $day = new Day(2019, 9, 27);
        $this->assertSame(27, $day->day);
    }

    public function testForwardsMethodCallsToCarbon()
    {
        $day = new Day(2019, 9, 27);
        $expected = CarbonImmutable::create(2019, 9, 27, 0)->toDateString();
        $this->assertSame($expected, $day->toDateString());
    }

    public function testDefaultsToANormalDay()
    {
        $day = new Day(2019, 9, 27);
        $this->assertFalse($day->isBlankDay());
    }

    public function testCanBeSetAsABlankDay()
    {
        $day = new Day(2019, 9, 27);
        $day->setBlankDay(true);
        $this->assertTrue($day->isBlankDay());
    }

    public function testReturnsTrueIfToday()
    {
        $today = CarbonImmutable::now();
        $day = new Day($today->year, $today->month, $today->day);
        $this->assertTrue($day->isToday());
    }

    public function testDoesNotReturnTheCarbonObjectWhenApplyingModifiers()
    {
        $day = new Day(2019, 9, 27);
        $this->assertInstanceOf(Day::class, $day->addDays(3));
        $this->assertSame('2019-09-30', $day->toDateString());
    }
}

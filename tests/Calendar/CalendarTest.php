<?php

namespace Tests\Calendar;

use Calendar\Calendar;
use Calendar\Day;
use Calendar\Week;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class CalendarTest extends TestCase
{
    public function testHasADefaultTimezone()
    {
        $calendar = new Calendar(2014, 7);
        $calendar->setWeekStart(CarbonInterface::SUNDAY);
        $timezone = $calendar->getTimezone();
        $this->assertInstanceOf(DateTimeZone::class, $timezone);
        $this->assertSame('UTC', $timezone->getName());
    }

    public function testCanSetTheTimezoneFromString()
    {
        $calendar = new Calendar(2014, 7);
        $calendar->setWeekStart(CarbonInterface::SUNDAY);
        $calendar->setTimezone('America/Chicago');

        $timezone = $calendar->getTimezone();
        $this->assertInstanceOf(DateTimeZone::class, $timezone);
        $this->assertSame('America/Chicago', $timezone->getName());
    }

    public function testCanSetTheTimezoneFromObject()
    {
        $calendar = new Calendar(2014, 7);
        $calendar->setWeekStart(CarbonInterface::SUNDAY);
        $calendar->setTimezone(new DateTimeZone('America/Denver'));

        $timezone = $calendar->getTimezone();
        $this->assertInstanceOf(DateTimeZone::class, $timezone);
        $this->assertSame('America/Denver', $timezone->getName());
    }

    public function testReturnsTheWeeksForAMonth()
    {
        $calendar = new Calendar(2014, 7);
        $calendar->setWeekStart(CarbonInterface::SUNDAY);
        $weeks = $calendar->getWeeks();
        $this->assertIsIterable($weeks);
        $this->assertCount(6, $weeks);

        $this->assertInstanceOf(Week::class, $weeks[0]);
        $days = $weeks[0]->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);
        $this->assertInstanceOf(Day::class, $days[0]);
        $this->assertTrue($days[0]->isBlankDay());
        $this->assertSame('2014-06-29', $days[0]->toDateString());

        $days = $weeks[4]->getDays();
        $this->assertTrue($days[6]->isBlankDay());
        $this->assertSame('2014-08-02', $days[6]->toDateString());
    }

    public function testReturnsOnlyTheNecessaryNumberOfWeeksForAMonth()
    {
        $calendar = new Calendar(2017, 8);
        $calendar->setWeekStart(CarbonInterface::SUNDAY);
        $calendar->setVariableWeeks(true);

        $weeks = $calendar->getWeeks();
        $this->assertIsIterable($weeks);
        $this->assertCount(5, $weeks);
        $this->assertInstanceOf(Week::class, $weeks[0]);

        $days = $weeks[0]->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);
        $this->assertInstanceOf(Day::class, $days[0]);
        $this->assertTrue($days[0]->isBlankDay());
        $this->assertSame('2017-07-30', $days[0]->toDateString());

        $this->assertInstanceOf(Day::class, $days[2]);
        $this->assertFalse($days[2]->isBlankDay());
        $this->assertSame('2017-08-01', $days[2]->toDateString());

        $days = $weeks[4]->getDays();
        $this->assertTrue($days[6]->isBlankDay());
        $this->assertSame('2017-09-02', $days[6]->toDateString());
    }

    public function testCanReturnAVariableNumberOfWeeks()
    {
        $calendar = new Calendar(2017, 4);
        $calendar->setWeekStart(CarbonInterface::SUNDAY);
        $calendar->setVariableWeeks(true);

        $weeks = $calendar->getWeeks();
        $this->assertIsIterable($weeks);
        $this->assertCount(6, $weeks);
        $this->assertSame('2017-04-01', $calendar->getFirstDay()->toDateString());
        $this->assertSame('2017-04-30', $calendar->getLastDay()->toDateString());
    }

    public function testReturnsTheDateForTheFirstDay()
    {
        $calendar = new Calendar(2014, 7);
        $calendar->setWeekStart(CarbonInterface::SUNDAY);
        $date = Carbon::create(2014, 7, 1, 0);
        $returned_date = $calendar->getFirstDay();
        $this->assertSame($date->toDateTimeString(), $returned_date->toDateTimeString());
    }

    public function testReturnsTheDateForTheLastDay()
    {
        $calendar = new Calendar(2014, 7);
        $calendar->setWeekStart(CarbonInterface::SUNDAY);
        $date = Carbon::create(2014, 7, 31, 0);
        $returned_date = $calendar->getLastDay();
        $this->assertSame($date->toDateTimeString(), $returned_date->toDateTimeString());
    }
}

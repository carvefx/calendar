<?php

namespace Tests\Calendar;

use Calendar\Day;
use Calendar\Week;
use Carbon\CarbonInterface;
use PHPUnit\Framework\TestCase;

class WeekTest extends TestCase
{
    public function testSetsTheCurrentMonthToTheStartDatesMonthIfNoneIsSpecified()
    {
        $start = new Day(2014, 7, 20);
        $week = new Week($start, null, CarbonInterface::SUNDAY);
        $this->assertSame(7, $week->getCurrentMonth());
    }

    public function testDetectsIfTheStartingDayIsNotTheFirstDayOfTheWeek()
    {
        $start = new Day(2014, 7, 1);
        $week = new Week($start, null, CarbonInterface::SUNDAY);
        $this->assertSame('2014-06-29', $week->getStartDate()->toDateString());
    }

    public function testSetsTheCurrentMonthToTheSpecifiedValue()
    {
        $start = new Day(2014, 7, 20);
        $currentMonth = 8;
        $week = new Week($start, $currentMonth, CarbonInterface::SUNDAY);

        $this->assertSame(8, $week->getCurrentMonth());
    }

    public function testReturnsTheDaysThatBelongToANormalWeek()
    {
        $start = new Day(2014, 7, 20);
        $week = new Week($start, null, CarbonInterface::SUNDAY);
        $days = $week->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);

        $this->assertSame('2014-07-20', $days[0]->toDateString());
        $this->assertSame('2014-07-26', $days[6]->toDateString());
    }

    public function testChecksIfADayBelongsToTheCurrentMonth()
    {
        $start = new Day(2014, 7, 20);
        $currentMonth = 7;
        $week = new Week($start, $currentMonth, CarbonInterface::SUNDAY);
        $day = new Day(2014, 7, 20);
        $this->assertTrue($week->currentMonthDay($day));
    }

    public function testChecksIfADayDoesNotBelongToTheCurrentMonth()
    {
        $start = new Day(2014, 7, 20);
        $currentMonth = 7;
        $week = new Week($start, $currentMonth, CarbonInterface::SUNDAY);
        $day = new Day(2014, 8, 20);
        $this->assertFalse($week->currentMonthDay($day));
    }

    public function testReturnsTheDaysThatBelongToAWeekWithBlankDays()
    {
        $start = new Day(2014, 6, 29);
        $currentMonth = 7;
        $week = new Week($start, $currentMonth, CarbonInterface::SUNDAY);

        $days = $week->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);

        $this->assertSame('2014-06-29', $days[0]->toDateString());
        $this->assertTrue($days[0]->isBlankDay());

        $this->assertSame('2014-06-30', $days[1]->toDateString());
        $this->assertTrue($days[1]->isBlankDay());

        $this->assertSame('2014-07-01', $days[2]->toDateString());
        $this->assertFalse($days[2]->isBlankDay());
    }

    public function testStartsOnCarbonsDefaultStartOfWeek()
    {
        $start = new Day(2017, 7, 1);
        $week = new Week($start, null, CarbonInterface::MONDAY);

        $days = $week->getDays();
        $this->assertSame(CarbonInterface::MONDAY, $days[0]->dayOfWeek);
    }

    public function testStartsOnWhateverDayOfTheWeekCarbonDoes()
    {
        $start = new Day(2017, 7, 1);
        $week = new Week($start, null, CarbonInterface::SUNDAY);

        $days = $week->getDays();
        $this->assertSame(CarbonInterface::SUNDAY, $days[0]->dayOfWeek);
    }

    public function testHandlesUsDaylightSavingsTimeStart()
    {
        $start = new Day(2017, 3, 12, 'America/New_York');
        $currentMonth = 11;
        $week = new Week($start, $currentMonth, CarbonInterface::SUNDAY);

        $days = $week->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);

        $this->assertSame('2017-03-12', $days[0]->toDateString());
        $this->assertSame('2017-03-13', $days[1]->toDateString());
        $this->assertSame('2017-03-14', $days[2]->toDateString());
        $this->assertSame('2017-03-15', $days[3]->toDateString());
        $this->assertSame('2017-03-16', $days[4]->toDateString());
        $this->assertSame('2017-03-17', $days[5]->toDateString());
        $this->assertSame('2017-03-18', $days[6]->toDateString());
    }

    public function testHandlesUsDaylightSavingsTimeEnd()
    {
        $start = new Day(2017, 11, 5, 'America/New_York');
        $currentMonth = 11;
        $week = new Week($start, $currentMonth, CarbonInterface::SUNDAY);

        $days = $week->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);

        $this->assertSame('2017-11-05', $days[0]->toDateString());
        $this->assertSame('2017-11-06', $days[1]->toDateString());
        $this->assertSame('2017-11-07', $days[2]->toDateString());
        $this->assertSame('2017-11-08', $days[3]->toDateString());
        $this->assertSame('2017-11-09', $days[4]->toDateString());
        $this->assertSame('2017-11-10', $days[5]->toDateString());
        $this->assertSame('2017-11-11', $days[6]->toDateString());
    }

    public function testHandlesUkDaylightSavingsTimeStart()
    {
        $start = new Day(2017, 3, 26, 'Europe/London');
        $currentMonth = 9;
        $week = new Week($start, $currentMonth, CarbonInterface::MONDAY);

        $days = $week->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);

        $this->assertSame('2017-03-20', $days[0]->toDateString());
        $this->assertSame('2017-03-21', $days[1]->toDateString());
        $this->assertSame('2017-03-22', $days[2]->toDateString());
        $this->assertSame('2017-03-23', $days[3]->toDateString());
        $this->assertSame('2017-03-24', $days[4]->toDateString());
        $this->assertSame('2017-03-25', $days[5]->toDateString());
        $this->assertSame('2017-03-26', $days[6]->toDateString());
    }

    public function testHandlesUkDaylightSavingsTimeEnd()
    {
        $start = new Day(2017, 10, 29, 'Europe/London');
        $currentMonth = 4;
        $week = new Week($start, $currentMonth, CarbonInterface::MONDAY);

        $days = $week->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);

        $this->assertSame('2017-10-23', $days[0]->toDateString());
        $this->assertSame('2017-10-24', $days[1]->toDateString());
        $this->assertSame('2017-10-25', $days[2]->toDateString());
        $this->assertSame('2017-10-26', $days[3]->toDateString());
        $this->assertSame('2017-10-27', $days[4]->toDateString());
        $this->assertSame('2017-10-28', $days[5]->toDateString());
        $this->assertSame('2017-10-29', $days[6]->toDateString());
    }

    public function testHandlesNzDaylightSavingsTimeStart()
    {
        $start = new Day(2017, 9, 24, 'Pacific/Auckland');
        $currentMonth = 9;
        $week = new Week($start, $currentMonth, CarbonInterface::SUNDAY);

        $days = $week->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);

        $this->assertSame('2017-09-24', $days[0]->toDateString());
        $this->assertSame('2017-09-25', $days[1]->toDateString());
        $this->assertSame('2017-09-26', $days[2]->toDateString());
        $this->assertSame('2017-09-27', $days[3]->toDateString());
        $this->assertSame('2017-09-28', $days[4]->toDateString());
        $this->assertSame('2017-09-29', $days[5]->toDateString());
        $this->assertSame('2017-09-30', $days[6]->toDateString());
    }

    public function testHandlesNzDaylightSavingsTimeEnd()
    {
        $start = new Day(2018, 4, 2, 'Pacific/Auckland');
        $currentMonth = 4;
        $week = new Week($start, $currentMonth, CarbonInterface::SUNDAY);

        $days = $week->getDays();
        $this->assertIsIterable($days);
        $this->assertCount(7, $days);

        $this->assertSame('2018-04-01', $days[0]->toDateString());
        $this->assertSame('2018-04-02', $days[1]->toDateString());
        $this->assertSame('2018-04-03', $days[2]->toDateString());
        $this->assertSame('2018-04-04', $days[3]->toDateString());
        $this->assertSame('2018-04-05', $days[4]->toDateString());
        $this->assertSame('2018-04-06', $days[5]->toDateString());
        $this->assertSame('2018-04-07', $days[6]->toDateString());
    }
}

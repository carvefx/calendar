<?php

namespace spec\Carvefx\Calendar;

use Carbon\Carbon;
use Carvefx\Calendar\Day;
use Carvefx\Calendar\Week;
use PhpSpec\ObjectBehavior;

class WeekSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Week::class);
    }

    function let()
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $start = new Day(2014, 7, 20);
        $this->beConstructedWith($start, $currentMonth = null);
    }

    function it_sets_the_current_month_to_the_start_dates_month_if_none_is_specified()
    {
        $this->getCurrentMonth()->shouldBe(7);
    }

    function it_detects_if_the_starting_day_is_not_the_first_day_of_the_week()
    {
        $start = new Day(2014, 7, 1);
        $this->beConstructedWith($start, $currentMonth = null);
        $this->getStartDate()->toDateString()->shouldBe('2014-06-29');
    }


    function it_sets_the_current_month_to_the_specified_value()
    {
        $start = new Day(2014, 7, 20);
        $currentMonth = 8;
        $this->beConstructedWith($start, $currentMonth);

        $this->getCurrentMonth()->shouldBe(8);
    }

    function it_returns_the_days_that_belong_to_a_normal_week()
    {
        $days = $this->getDays();
        $days->shouldBeArray();
        $days->shouldHaveCount(7);

        $days[0]->toDateString()->shouldBe('2014-07-20');
        $days[6]->toDateString()->shouldBe('2014-07-26');
    }

    function it_checks_if_a_day_belongs_to_the_current_Month()
    {
        $start = new Day(2014, 7, 20);
        $currentMonth = 7;
        $this->beConstructedWith($start, $currentMonth);
        $day = new Day(2014, 7, 20);
        $this->currentMonthDay($day)->shouldReturn(true);
    }

    function it_checks_if_a_day_does_not_belong_to_the_current_Month()
    {
        $start = new Day(2014, 7, 20);
        $currentMonth = 7;
        $this->beConstructedWith($start, $currentMonth);
        $day = new Day(2014, 8, 20);
        $this->currentMonthDay($day)->shouldReturn(false);
    }

    function it_returns_the_days_that_belong_to_a_week_with_blank_days()
    {
        $start = new Day(2014, 6, 29);
        $currentMonth = 7;
        $this->beConstructedWith($start, $currentMonth);

        $days = $this->getDays();
        $days->shouldBeArray();
        $days->shouldHaveCount(7);

        $days[0]->toDateString()->shouldBe('2014-06-29');
        $days[0]->isBlankDay()->shouldBe(true);

        $days[1]->toDateString()->shouldBe('2014-06-30');
        $days[1]->isBlankDay()->shouldBe(true);

        $days[2]->toDateString()->shouldBe('2014-07-01');
        $days[2]->isBlankDay()->shouldBe(false);
    }

    function it_starts_on_carbons_default_start_of_week()
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);

        $start = new Day(2017, 7, 1);
        $this->beConstructedWith($start, null);

        $days = $this->getDays();
        $days[0]->dayOfWeek->shouldBe(Carbon::MONDAY);
    }

    function it_starts_on_whatever_day_of_the_week_carbon_does()
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $start = new Day(2017, 7, 1);
        $this->beConstructedWith($start, null);

        $days = $this->getDays();
        $days[0]->dayOfWeek->shouldBe(Carbon::SUNDAY);
    }

    function it_handles_us_daylight_savings_time_start()
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $start = new Day(2017, 3, 12, 'America/New_York');
        $currentMonth = 11;
        $this->beConstructedWith($start, $currentMonth);

        $days = $this->getDays();
        $days->shouldBeArray();
        $days->shouldHaveCount(7);

        $days[0]->toDateString()->shouldBe('2017-03-12');
        $days[1]->toDateString()->shouldBe('2017-03-13');
        $days[2]->toDateString()->shouldBe('2017-03-14');
        $days[3]->toDateString()->shouldBe('2017-03-15');
        $days[4]->toDateString()->shouldBe('2017-03-16');
        $days[5]->toDateString()->shouldBe('2017-03-17');
        $days[6]->toDateString()->shouldBe('2017-03-18');
    }

    function it_handles_us_daylight_savings_time_end()
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $start = new Day(2017, 11, 5, 'America/New_York');
        $currentMonth = 11;
        $this->beConstructedWith($start, $currentMonth);

        $days = $this->getDays();
        $days->shouldBeArray();
        $days->shouldHaveCount(7);

        $days[0]->toDateString()->shouldBe('2017-11-05');
        $days[1]->toDateString()->shouldBe('2017-11-06');
        $days[2]->toDateString()->shouldBe('2017-11-07');
        $days[3]->toDateString()->shouldBe('2017-11-08');
        $days[4]->toDateString()->shouldBe('2017-11-09');
        $days[5]->toDateString()->shouldBe('2017-11-10');
        $days[6]->toDateString()->shouldBe('2017-11-11');
    }

    function it_handles_uk_daylight_savings_time_start()
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);

        $start = new Day(2017, 3, 26, 'Europe/London');
        $currentMonth = 9;
        $this->beConstructedWith($start, $currentMonth);

        $days = $this->getDays();
        $days->shouldBeArray();
        $days->shouldHaveCount(7);

        $days[0]->toDateString()->shouldBe('2017-03-20');
        $days[1]->toDateString()->shouldBe('2017-03-21');
        $days[2]->toDateString()->shouldBe('2017-03-22');
        $days[3]->toDateString()->shouldBe('2017-03-23');
        $days[4]->toDateString()->shouldBe('2017-03-24');
        $days[5]->toDateString()->shouldBe('2017-03-25');
        $days[6]->toDateString()->shouldBe('2017-03-26');
    }

    function it_handles_uk_daylight_savings_time_end()
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);

        $start = new Day(2017, 10, 29, 'Europe/London');
        $currentMonth = 4;
        $this->beConstructedWith($start, $currentMonth);

        $days = $this->getDays();
        $days->shouldBeArray();
        $days->shouldHaveCount(7);

        $days[0]->toDateString()->shouldBe('2017-10-23');
        $days[1]->toDateString()->shouldBe('2017-10-24');
        $days[2]->toDateString()->shouldBe('2017-10-25');
        $days[3]->toDateString()->shouldBe('2017-10-26');
        $days[4]->toDateString()->shouldBe('2017-10-27');
        $days[5]->toDateString()->shouldBe('2017-10-28');
        $days[6]->toDateString()->shouldBe('2017-10-29');
    }

    function it_handles_nz_daylight_savings_time_start()
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $start = new Day(2017, 9, 24, 'Pacific/Auckland');
        $currentMonth = 9;
        $this->beConstructedWith($start, $currentMonth);

        $days = $this->getDays();
        $days->shouldBeArray();
        $days->shouldHaveCount(7);

        $days[0]->toDateString()->shouldBe('2017-09-24');
        $days[1]->toDateString()->shouldBe('2017-09-25');
        $days[2]->toDateString()->shouldBe('2017-09-26');
        $days[3]->toDateString()->shouldBe('2017-09-27');
        $days[4]->toDateString()->shouldBe('2017-09-28');
        $days[5]->toDateString()->shouldBe('2017-09-29');
        $days[6]->toDateString()->shouldBe('2017-09-30');
    }

    function it_handles_nz_daylight_savings_time_end()
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $start = new Day(2018, 4, 2, 'Pacific/Auckland');
        $currentMonth = 4;
        $this->beConstructedWith($start, $currentMonth);

        $days = $this->getDays();
        $days->shouldBeArray();
        $days->shouldHaveCount(7);

        $days[0]->toDateString()->shouldBe('2018-04-01');
        $days[1]->toDateString()->shouldBe('2018-04-02');
        $days[2]->toDateString()->shouldBe('2018-04-03');
        $days[3]->toDateString()->shouldBe('2018-04-04');
        $days[4]->toDateString()->shouldBe('2018-04-05');
        $days[5]->toDateString()->shouldBe('2018-04-06');
        $days[6]->toDateString()->shouldBe('2018-04-07');
    }
}

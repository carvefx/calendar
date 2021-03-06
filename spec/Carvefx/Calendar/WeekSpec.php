<?php

namespace spec\Carvefx\Calendar;

use Carvefx\Calendar\Day;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WeekSpec extends ObjectBehavior
{
  function it_is_initializable()
  {
    $this->shouldHaveType('Carvefx\Calendar\Week');
  }

  function let()
  {
    $start = new Day(2014, 7, 20);
    $this->beConstructedWith($start, $current_month = null);
  }

  function it_sets_the_current_month_to_the_start_dates_month_if_none_is_specified()
  {
    $this->getCurrentMonth()->shouldBe(7);
  }

  function it_detects_if_the_starting_day_is_not_the_first_day_of_the_week()
  {
    $start = new Day(2014, 7, 1);
    $this->beConstructedWith($start, $current_month = null);
    $this->getStartDate()->toDateString()->shouldBe('2014-06-29');
  }


  function it_sets_the_current_month_to_the_specified_value()
  {
    $start = new Day(2014, 7, 20);
    $current_month = 8;
    $this->beConstructedWith($start, $current_month);

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
    $current_month = 7;
    $this->beConstructedWith($start, $current_month);
    $day = new Day(2014, 7, 20);
    $this->currentMonthDay($day)->shouldReturn(true);
  }

  function it_checks_if_a_day_does_not_belong_to_the_current_Month()
  {
    $start = new Day(2014, 7, 20);
    $current_month = 7;
    $this->beConstructedWith($start, $current_month);
    $day = new Day(2014, 8, 20);
    $this->currentMonthDay($day)->shouldReturn(false);
  }

  function it_returns_the_days_that_belong_to_a_week_with_blank_days()
  {
    $start = new Day(2014, 6, 29);
    $current_month = 7;
    $this->beConstructedWith($start, $current_month);

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


}

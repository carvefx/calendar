<?php

namespace spec\Carvefx\Calendar;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Carbon\Carbon;

class CalendarSpec extends ObjectBehavior
{
  function it_is_initializable()
  {
    $this->shouldHaveType('Carvefx\Calendar\Calendar');
  }


  function let()
  {
    $this->beConstructedWith(2014, 7);
  }

  function it_returns_the_weeks_for_a_month()
  {
    $weeks = $this->getWeeks();
    $weeks->shouldBeArray();
    $weeks->shouldHaveCount(5);

    $weeks[0]->shouldBeAnInstanceOf('Carvefx\Calendar\Week');
    $days = $weeks[0]->getDays();
    $days->shouldBeArray();
    $days->shouldHaveCount(7);
    $days[0]->shouldBeAnInstanceOf('Carvefx\Calendar\Day');
    $days[0]->isBlankDay()->shouldReturn(true);
    $days[0]->toDateString()->shouldReturn('2014-06-29');

    $days = $weeks[4]->getDays();
    $days[6]->isBlankDay()->shouldReturn(true);
    $days[6]->toDateString()->shouldReturn('2014-08-02');
  }

  function it_returns_the_date_for_the_first_day()
  {
    $date = Carbon::create(2014, 7, 1, 0);
    $returned_date = $this->getFirstDay();
    $returned_date->toDateTimeString()->shouldBe($date->toDateTimeString());
  }

  function it_returns_the_date_for_the_last_day()
  {
    $date = Carbon::create(2014, 7, 31, 0);
    $returned_date = $this->getLastDay();
    $returned_date->toDateTimeString()->shouldBe($date->toDateTimeString());
  }
}
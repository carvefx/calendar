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
    $this->beConstructedWith($start);
  }

  function it_returns_the_days_that_belong_to_a_normal_week()
  {
    $days = $this->getDays();
    $days->shouldBeArray();
    $days->shouldHaveCount(7);

    $days[0]->toDateString()->shouldBe('2014-07-20');
    $days[6]->toDateString()->shouldBe('2014-07-26');
  }


}

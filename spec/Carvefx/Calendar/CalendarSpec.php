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
    $this->setYear(2014);
    $this->setMonth(7);
  }

  function it_returns_the_calendar_for_a_month()
  {
    $expected = [
      [
        'blank_days' => [29, 30],
        'days' => [1, 2, 3, 4, 5]
      ],
      [
        'blank_days' => [],
        'days' => [6, 7, 8, 9, 10, 11, 12]
      ],
      [
        'blank_days' => [],
        'days' => [13, 14, 15, 16, 17, 18, 19]
      ],
      [
        'blank_days' => [],
        'days' => [20, 21, 22, 23, 24, 25, 26]
      ],
      [
        'blank_days' => [1, 2],
        'days' => [27, 28, 29, 30, 31]
      ],
      [
        'blank_days' => [3, 4, 5, 6, 7, 8, 9],
        'days' => []
      ]
    ];
    $this->getCalendar()->shouldReturn($expected);
  }

  function it_fills_in_blank_weeks()
  {
    $weeks =
      [
        [
          'blank_days' => [29, 30],
          'days' => [1, 2, 3, 4, 5]
        ],
        [
          'blank_days' => [],
          'days' => [6, 7, 8, 9, 10, 11, 12]
        ],
        [
          'blank_days' => [],
          'days' => [13, 14, 15, 16, 17, 18, 19]
        ],
        [
          'blank_days' => [],
          'days' => [20, 21, 22, 23, 24, 25, 26]
        ],
        [
          'blank_days' => [1, 2],
          'days' => [27, 28, 29, 30, 31]
        ]
    ];

    $expected = $weeks;
    $expected[] = ['blank_days' => [3, 4, 5, 6, 7, 8, 9], 'days' => []];
    $this->fillBlankWeeks($weeks, 1)->shouldReturn($expected);
  }

  function it_fills_in_blank_weeks_when_two_are_needed()
  {
    $this->setYear(2015);
    $this->setMonth(2);
    $expected =
      [
        ['blank_days' => [25, 26, 27, 28, 29, 30, 31], 'days' => []],
        [
          'blank_days' => [],
          'days' => [1, 2, 3, 4, 5, 6, 7]
        ],
        [
          'blank_days' => [],
          'days' => [8, 9, 10, 11, 12, 13, 14]
        ],
        [
          'blank_days' => [],
          'days' => [15, 16, 17, 18, 19, 20, 21]
        ],
        [
          'blank_days' => [],
          'days' => [22, 23, 24, 25, 26, 27, 28]
        ],
        ['blank_days' => [1, 2, 3, 4, 5, 6, 7], 'days' => []]
      ];

    $weeks = $expected;
    unset($weeks[0]);
    unset($weeks[5]);
    $weeks = array_values($weeks);
    $this->fillBlankWeeks($weeks, 2)->shouldReturn($expected);
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

  function it_returns_the_latest_blank_date_processed()
  {
    $weeks =
      [
        [
          'blank_days' => [1, 2],
          'days' => [27, 28, 29, 30, 31]
        ]
      ];

    $date = $this->getLastProcessed($weeks);
    $date->toDateString()->shouldBe('2014-08-02');
  }

  function it_returns_the_latest_date_processed()
  {
    $weeks =
      [
        [
          'blank_days' => [],
          'days' => [27, 28, 29, 30, 31]
        ]
      ];

    $date = $this->getLastProcessed($weeks);
    $date->toDateString()->shouldBe('2014-07-31');
  }

  function it_returns_the_first_date_processed()
  {
    $weeks =
      [
        [
          'blank_days' => [],
          'days' => [27, 28, 29, 30, 31]
        ]
      ];

    $date = $this->getFirstProcessed($weeks);
    $date->toDateString()->shouldBe('2014-07-27');
  }



}
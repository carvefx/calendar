<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;

class Calendar
{

  /**
   * The number of weeks a calendar month displays
   * (This includes blank days from other months)
   */
  const WEEKS_IN_MONTH = 6;

  /**
   * @var
   */
  private $month;

  /**
   * @var
   */
  private $year;

  /**
   * @param mixed $month
   */
  public function setMonth($month)
  {
    $this->month = $month;
  }

  /**
   * @return mixed
   */
  public function getMonth()
  {
    return $this->month;
  }

  /**
   * @param mixed $year
   */
  public function setYear($year)
  {
    $this->year = $year;
  }

  /**
   * @return mixed
   */
  public function getYear()
  {
    return $this->year;
  }

  /**
   * @return Day
   */
  public function getFirstDay()
  {
    return new Day($this->year, $this->month, 1);
  }

  /**
   * @return \Carbon\Carbon
   */
  public function getLastDay()
  {
    $start = $this->getFirstDay();
    $last = $start->daysInMonth;
    $end = $start->setDate($this->year, $this->month, $last);

    return $end;
  }

  /**
   * @return array
   */
  public function getWeeks()
  {
    $last_day = $this->getLastDay();
    $last_week = $last_day->weekOfMonth;
    $first_day = $this->getFirstDay();

    $weeks = [];
    for($week = 1; $week <= $last_week; $week++) {
      $curr_week = new Week(clone $first_day, $first_day->month);
      $weeks[] = $curr_week;
      $first_day->addDays(7);
    }

    return $weeks;
  }
}

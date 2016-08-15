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

  public function __construct($year, $month)
  {
    $this->setYear($year);
    $this->setMonth($month);
  }

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
    $first_day = $this->getFirstDay();
    $last_day = $this->getLastDay();

    $weeks = [];
    for($week = 1; $week <= self::WEEKS_IN_MONTH; $week++) {
      $curr_week = new Week(clone $first_day, $last_day->month);
      $weeks[] = $curr_week;
      $first_day->addDays(7);
    }

    return $weeks;
  }
}

<?php

namespace Carvefx\Calendar;

class Calendar
{
  /**
   * The number of weeks a calendar month displays
   * (This includes blank days from other months)
   */
  const WEEKS_IN_MONTH = 6;

  /**
   * @var int
   */
  private $month;

  /**
   * @var int
   */
  private $year;

  public function __construct($year, $month)
  {
    $this->setYear($year);
    $this->setMonth($month);
  }

  /**
   * @param int $month
   * @throws \InvalidArgumentException
   */
  public function setMonth($month)
  {
    if (! is_int($month)) {
      throw new \InvalidArgumentException('setMonth requires an integer value');
    }

    $this->month = $month;
  }

  /**
   * @return int
   */
  public function getMonth()
  {
    return $this->month;
  }

  /**
   * @param int $year
   * @throws \InvalidArgumentException
   */
  public function setYear($year)
  {
    if (! is_int($year)) {
      throw new \InvalidArgumentException('setYear requires an integer value');
    }

    $this->year = $year;
  }

  /**
   * @return int
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

    return $start->endOfMonth()->startOfDay();
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

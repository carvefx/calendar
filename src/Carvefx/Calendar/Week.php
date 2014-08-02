<?php

namespace Carvefx\Calendar;

class Week
{
  /**
   * Total number of days in a week
   */
  const DAYS_IN_WEEK = 7;

  /**
   * @var Day
   */
  private $date_start;

  /**
   * @var array
   */
  private $days = [];

  /**
   * @var bool|mixed
   */
  private $current_month;

  /**
   * @param Day   $date_start
   * @param mixed $current_month
   */
  public function __construct(Day $date_start, $current_month = false)
  {
    $this->setStartDate($date_start);
    $this->setCurrentMonth($current_month);
    $this->addDay($this->date_start);
    $this->generateWeek();
  }

  /**
   * @param Day $date_start
   */
  private function setStartDate(Day $date_start)
  {
    $day_of_week = $date_start->dayOfWeek;
    if($day_of_week != 1) {
      $date_start->subDays($day_of_week);
    }

    $this->date_start = $date_start;
  }

  /**
   * @return Day
   */
  public function getStartDate()
  {
    return $this->date_start;
  }

  /**
   * @param $current_month
   */
  private function setCurrentMonth($current_month)
  {
    if (!$current_month) {
      $this->current_month = $this->date_start->month;
    } else {
      $this->current_month = $current_month;
    }
  }

  /**
   * @return bool|mixed
   */
  public function getCurrentMonth()
  {
    return $this->current_month;
  }

  /**
   * Generates a week from the specified start date
   */
  private function generateWeek()
  {
    $date = clone $this->date_start;
    for ($day = 1; $day < self::DAYS_IN_WEEK; $day++) {
      $curr_day = clone $date->addDays(1);
      $this->addDay($curr_day);
    }
  }

  /**
   * Adds a day to the days property
   * Goes through a check first
   * @param Day $day
   */
  private function addDay(Day $day)
  {
    if(! $this->currentMonthDay($day)) {
      $day->setBlankDay(true);
    } else {
      $day->setBlankDay(false);
    }

    $this->days[] = $day;
  }

  /**
   * @return array
   */
  public function getDays()
  {
    return $this->days;
  }

  /**
   * Determines whether a day is part of the currently
   * selected month or not
   * @param Day $day
   * @return bool
   */
  public function currentMonthDay(Day $day)
  {
    if ($day->month == $this->getCurrentMonth()) {
      return true;
    }

    return false;
  }
}

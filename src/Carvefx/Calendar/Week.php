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
   * @param Day $date_start
   */
  public function __construct(Day $date_start)
  {
    $this->setStartDate($date_start);
    $this->generateWeek();
  }

  /**
   * @param Day $date_start
   */
  private function setStartDate(Day $date_start)
  {
    $this->date_start = $date_start;
    $this->days[] = $date_start;
  }

  /**
   * Generates a week from the specified start date
   */
  private function generateWeek()
  {
    $curr_date = clone $this->date_start;
    for($day = 1; $day < self::DAYS_IN_WEEK; $day++) {
      $this->days[] = $curr_date->addDays(1);
    }
  }

  /**
   * @return array
   */
  public function getDays()
  {
    return $this->days;
  }
}

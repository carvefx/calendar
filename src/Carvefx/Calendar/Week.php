<?php

namespace Carvefx\Calendar;

class Week
{
  const DAYS_IN_WEEK = 7;

  /**
   * @var Day
   */
  private $date_start;

  /**
   * @var array
   */
  private $days = [];

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

  private function generateWeek()
  {
    $curr_date = clone $this->date_start;
    for($day = 1; $day < self::DAYS_IN_WEEK; $day++) {
      $this->days[] = $curr_date->addDays(1);
    }
  }

  public function getDays()
  {
    //var_dump($this->days);
    return $this->days;
  }
}

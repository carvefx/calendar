<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;

class Calendar
{

  /**
   * The number of days a week has
   */
  const DAYS_IN_WEEK = 7;

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
   * @return static
   */
  public function getFirstDay()
  {
    return $this->getFirstDayByYearMonth($this->year, $this->month);
  }

  /**
   * @param $year
   * @param $month
   * @return static
   */
  public function getFirstDayByYearMonth($year, $month)
  {
    return Carbon::create($year, $month, 1, 0);
  }

  /**
   * @return \Carbon\Carbon
   */
  public function getLastDay()
  {
    return $this->getLastDayByYearMonth($this->year, $this->month);
  }

  /**
   * @param $year
   * @param $month
   * @return \Carbon\Carbon
   */
  public function getLastDayByYearMonth($year, $month)
  {
    $date = Carbon::create($year, $month, 1, 0);
    $last = $date->daysInMonth;
    $date->setDate($year, $month, $last, 0);

    return $date;
  }

  /**
   * @return array
   */
  public function getCalendar()
  {
    $last_day = $this->getLastDay();
    $last_week = $last_day->weekOfMonth;

    $weeks = [];
    for($week = 1; $week <= $last_week; $week++) {
      $weeks[] = [
        'blank_days' => $this->getBlankDaysByWeek($week),
        'days' => $this->getDaysByWeek($week)
      ];
    }

    $processed_weeks = count($weeks);
    if($processed_weeks < self::WEEKS_IN_MONTH) {
      $needed = self::WEEKS_IN_MONTH - $processed_weeks;
      $weeks = $this->fillBlankWeeks($weeks, $needed);
    }

    return $weeks;
  }

  /**
   * @param $week
   * @return array
   */
  public function getBlankDaysByWeek($week)
  {
    $blank_days = [];
    if($week == 1) {
      $start_date = $this->getFirstDay();
      if($start_date->day == $start_date->dayOfWeek) {
        return $blank_days;
      }

      $missing_days = $start_date->dayOfWeek;
      for($day = 1; $day <= $missing_days; $day++) {
        $blank_days[] = $start_date->subDay(1)->day;
      }
    } else {
      $last_date = $this->getLastDay();
      if($last_date->weekOfMonth != $week) {
        return $blank_days;
      }

      $day = $last_date->dayOfWeek;
      for($day; $day < 6; $day++) {
        $blank_days[] = $last_date->addDay(1)->day;
      }
    }

    sort($blank_days, SORT_NUMERIC);
    return array_values($blank_days);
  }

  /**
   * @param $week
   * @return array
   */
  public function getDaysByWeek($week)
  {
    $days = [];
    $start_date = $this->getFirstDay();

    if($week != $start_date->weekOfMonth) {
      $start_date = $this->getFirstDayByWeek($week);
    }

    $days[] = $start_date->day;
    $max_days = 6;
    $end_date = $this->getLastDay();

    if($end_date->weekOfMonth == $week) {
      $max_days = $end_date->dayOfWeek;
    }

    for($day = $start_date->dayOfWeek; $day < $max_days; $day++) {
      $days[] = $start_date->addDays(1)->day;
    }

    return $days;
  }

  /**
   * @param $week
   * @return \Carbon\Carbon
   */
  public function getFirstDayByWeek($week)
  {
    $first_day = $this->getFirstDay();

    if ($week == 1) {
      return $first_day;
    }

    $week = $first_day->addWeeks($week - 1);
    $day_of_week = $week->dayOfWeek;

    if ($day_of_week == 1) {
      return $week;
    }

    return $week->subDays($day_of_week);
  }

  /**
   * @param $weeks
   * @param $needed
   * @return array
   */
  public function fillBlankWeeks($weeks, $needed)
  {
    if($needed == 0) {
      return $weeks;
    }

    if($needed == 1) {
      $start_date = $this->getLastProcessed($weeks);
      $days = [];

      for($day = 1; $day <= 7; $day++) {
        $days[] = $start_date->addDays(1)->day;
      }

      $weeks[] = ['blank_days' => array_values($days), 'days' => []];
    }

    if($needed == 2) {
      $start_date = $this->getLastProcessed($weeks);
      $days = [];

      for($day = 1; $day <= 7; $day++) {
        $days[] = $start_date->addDays(1)->day;
      }

      $weeks[] = ['blank_days' => $days, 'days' => []];

      $days = [];
      $start_date = $this->getFirstProcessed($weeks);
      for($day = 1; $day <= 7; $day++) {
        $days[] = $start_date->subDays(1)->day;
      }

      sort($days, SORT_NUMERIC);
      $weeks[-1] = ['blank_days' => array_values($days), 'days' => []];
      ksort($weeks);
    }
    return array_values($weeks);
  }

  /**
   * @param $weeks
   * @return static
   */
  public function getLastProcessed($weeks)
  {
    $total_weeks = count($weeks);
    $last_week = $weeks[$total_weeks - 1];
    if(!empty($last_week['blank_days'])){
      $last_blank = count($last_week['blank_days']) - 1;
      $last = $last_week['blank_days'][$last_blank];
      $date = $this->getLastDay()->addDays(1);
    } else {
      $last_day = count($last_week['days']) - 1;
      $last = $last_week['days'][$last_day];
      $date = $this->getFirstDay();
    }

    $date->day($last);
    return $date;
  }

  /**
   * @param $weeks
   * @return static
   */
  public function getFirstProcessed($weeks)
  {
    $first_week = $weeks[0];
    if(!empty($first_week['blank_days'])){
      $first = $first_week['blank_days'][0];
      $date = $this->getLastDay()->addDays(1);
    } else {
      $first = $first_week['days'][0];
      $date = $this->getFirstDay();
    }

    $date->day($first);
    return $date;
  }
}

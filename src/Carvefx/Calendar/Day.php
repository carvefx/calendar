<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;

class Day
{
  /**
   * @var \Carbon\Carbon
   */
  private $carbon;

  /**
   * @var bool
   */
  private $blank_day = false;

  /**
   * @param $year
   * @param $month
   * @param $day
   */
  public function __construct($year, $month, $day)
  {
    $this->carbon = Carbon::create($year, $month, $day, 0);
    return $this;
  }

  /**
   * @param $value
   * @throws \InvalidArgumentException
   */
  public function setBlankDay($value)
  {
    if(! is_bool($value)) {
      throw new \InvalidArgumentException('setBlankDay requires a boolean value');
    }

    $this->blank_day = $value;
  }

  /**
   * A "blank" day refers to days that appear
   * on the current months calendar but are physically part of the
   * previous or next months
   *
   * @return bool
   */
  public function isBlankDay()
  {
    return $this->blank_day;
  }

  /**
   * Attempts to query Carbon for the property
   * @param $prop
   * @return \DateTimeZone|int|string
   */
  public function __get($prop)
  {
    return $this->carbon->$prop;
  }

  /**
   * Attempts to query Carbon for the property
   * @param $method
   * @param $args
   * @return mixed
   */
  public function __call($method, $args)
  {
    return $this->carbon->$method($args);
  }
}

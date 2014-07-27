<?php


namespace Carvefx\Calendar;


class AbstractCarbonWrapper
{
  /**
   * @var \Carbon\Carbon
   */
  protected $carbon;

  /**
   * Attempts to query Carbon for the property
   *
   * @param $prop
   * @return \DateTimeZone|int|string
   */
  public function __get($prop)
  {
    return $this->carbon->$prop;
  }

  /**
   * Attempts to query Carbon for the property
   *
   * @param $method
   * @param $args
   * @return mixed
   */
  public function __call($method, $args)
  {
    $result = call_user_func_array([$this->carbon, $method], $args);

    if (!$this->isModifierMethod($method)) {
      return $result;
    }

    return $this;
  }

  /**
   * @param $method
   * @return bool
   */
  protected function isModifierMethod($method)
  {
    $pattern = '/^add|^sub/';
    preg_match($pattern, $method, $matches);
    if (count($matches)) {
      return true;
    }

    return false;
  }
}
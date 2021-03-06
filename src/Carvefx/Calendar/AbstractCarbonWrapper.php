<?php


namespace Carvefx\Calendar;


abstract class AbstractCarbonWrapper
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
    if (! $this->isModifierMethod($method)) {
      return call_user_func_array([$this->carbon, $method], $args);
    }

    $this->carbon = call_user_func_array([$this->carbon, $method], $args);
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


  /**
   * Force copy of the carbon object to
   * avoid it being passed by reference.
   */
  public function __clone()
  {
    $this->carbon = clone $this->carbon;
  }
}
<?php

namespace Calendar;

/**
 * @mixin \Carbon\CarbonImmutable
 */
abstract class AbstractCarbonWrapper
{
    /**
     * @var \Carbon\CarbonImmutable
     */
    protected $carbon;

    /**
     * Attempts to query Carbon for the property
     *
     * @param $prop
     *
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
     *
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

    protected function isModifierMethod(string $method): bool
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
        $this->carbon = $this->carbon->copy();
    }
}

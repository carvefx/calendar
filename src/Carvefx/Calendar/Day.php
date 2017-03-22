<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;

class Day extends AbstractCarbonWrapper
{
    /**
     * @var bool
     */
    private $blank_day = false;

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     */
    public function __construct($year, $month, $day)
    {
        $this->carbon = Carbon::create($year, $month, $day, 0);

        return $this;
    }

    /**
     * @param bool $value
     *
     * @throws \InvalidArgumentException
     */
    public function setBlankDay($value)
    {
        if (! is_bool($value)) {
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
}

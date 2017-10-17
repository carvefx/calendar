<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;

class Day extends AbstractCarbonWrapper
{
    /**
     * @var bool
     */
    private $blankDay = false;

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @param \DateTimeZone|string $timezone
     */
    public function __construct($year, $month, $day, $timezone = 'UTC')
    {
        $hour = 5;

        if (($timezone instanceof \DateTimeZone && $timezone->getName() === 'UTC') || $timezone === 'UTC') {
            $hour = 0;
        }

        $this->carbon = Carbon::create($year, $month, $day, $hour, 0, 0, $timezone);
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

        $this->blankDay = $value;
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
        return $this->blankDay;
    }
}

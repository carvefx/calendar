<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;

/**
 * @mixin \Carbon\Carbon
 */
class Day extends AbstractCarbonWrapper
{
    /**
     * @var bool
     */
    private $blankDay = false;

    /**
     * @param \DateTimeZone|string $timezone
     */
    public function __construct(int $year, int $month, int $day, $timezone = 'UTC')
    {
        $hour = 5;

        if (($timezone instanceof \DateTimeZone && $timezone->getName() === 'UTC') || $timezone === 'UTC') {
            $hour = 0;
        }

        $this->carbon = Carbon::create($year, $month, $day, $hour, 0, 0, $timezone);
    }

    public function setBlankDay(bool $value): void
    {
        $this->blankDay = $value;
    }

    /**
     * A "blank" day refers to days that appear
     * on the current months calendar but are physically part of the
     * previous or next months
     */
    public function isBlankDay(): bool
    {
        return $this->blankDay;
    }
}

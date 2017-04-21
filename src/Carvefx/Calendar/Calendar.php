<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;
use DateTimeZone;

class Calendar
{
    /**
     * The number of weeks a calendar month displays
     * (This includes blank days from other months)
     */
    const WEEKS_IN_MONTH = 6;

    /**
     * @var int
     */
    private $month;

    /**
     * @var int
     */
    private $year;

    /**
     * @var \DateTimeZone
     */
    private $timezone;

    /**
     * @var bool
     */
    private $variableWeeks = false;

    public function __construct($year, $month, $timezone = 'UTC')
    {
        $this->setYear($year);
        $this->setMonth($month);
        $this->setTimezone($timezone);
    }

    /**
     * @param int $month
     *
     * @throws \InvalidArgumentException
     */
    public function setMonth($month)
    {
        if (! is_int($month)) {
            throw new \InvalidArgumentException('setMonth requires an integer value');
        }

        $this->month = $month;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $year
     *
     * @throws \InvalidArgumentException
     */
    public function setYear($year)
    {
        if (! is_int($year)) {
            throw new \InvalidArgumentException('setYear requires an integer value');
        }

        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param \DateTimeZone|string $timezone
     *
     * @throws \InvalidArgumentException
     */
    public function setTimezone($timezone)
    {
        if (! ($timezone instanceof DateTimeZone || is_string($timezone))) {
            throw new \InvalidArgumentException('setTimezone requires a DateTimeZone instance or a timezone string');
        }

        $this->timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param bool $variableWeeks
     */
    public function setVariableWeeks($variableWeeks)
    {
        $this->variableWeeks = $variableWeeks;
    }

    /**
     * @return bool
     */
    public function isVariableWeeks()
    {
        return $this->variableWeeks;
    }

    /**
     * @param Day $firstDay
     *
     * @return int
     */
    protected function getWeekCount(Day $firstDay)
    {
        $startOfWeek = Carbon::getWeekStartsAt();

        return ceil(((($firstDay->dayOfWeek - $startOfWeek + 7) % 7) + $firstDay->daysInMonth) / 7);
    }

    /**
     * @return Day
     */
    public function getFirstDay()
    {
        return new Day($this->year, $this->month, 1, $this->timezone);
    }

    /**
     * @return \Carbon\Carbon
     */
    public function getLastDay()
    {
        $start = $this->getFirstDay();

        return $start->endOfMonth()->startOfDay();
    }

    /**
     * @return Week[]
     */
    public function getWeeks()
    {
        $firstDay = $this->getFirstDay();
        $lastDay = $this->getLastDay();
        $numberOfWeeks = $this->isVariableWeeks() ? $this->getWeekCount($firstDay) : self::WEEKS_IN_MONTH;

        $weeks = [];
        for ($week = 1; $week <= $numberOfWeeks; $week++) {
            $currWeek = new Week(clone $firstDay, $lastDay->month);
            $weeks[] = $currWeek;
            $firstDay->addDays(7);
        }

        return $weeks;
    }
}

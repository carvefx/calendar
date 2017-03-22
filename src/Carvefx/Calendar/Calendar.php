<?php

namespace Carvefx\Calendar;

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
        if (! ($timezone instanceof \DateTimeZone || is_string($timezone))) {
            throw new \InvalidArgumentException('setTimezone requires a DateTimeZone instance or a timezone string');
        }

        if ($timezone instanceof \DateTimeZone) {
            $this->timezone = $timezone;
        } else {
            $this->timezone = new \DateTimeZone($timezone);
        }
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimezone()
    {
        return $this->timezone;
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
     * @return array
     */
    public function getWeeks()
    {
        $first_day = $this->getFirstDay();
        $last_day = $this->getLastDay();

        $weeks = [];
        for ($week = 1; $week <= self::WEEKS_IN_MONTH; $week++) {
            $curr_week = new Week(clone $first_day, $last_day->month);
            $weeks[] = $curr_week;
            $first_day->addDays(7);
        }

        return $weeks;
    }
}

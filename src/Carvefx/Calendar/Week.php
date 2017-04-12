<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;

class Week
{
    /**
     * @var Day
     */
    private $date_start;

    /**
     * @var array
     */
    private $days = [];

    /**
     * @var int
     */
    private $current_month;

    /**
     * @param Day $date_start
     * @param mixed $current_month
     */
    public function __construct(Day $date_start, $current_month = null)
    {
        $this->setStartDate($date_start);
        $this->setCurrentMonth($current_month);
        $this->addDay($this->date_start);
        $this->generateWeek();
    }

    /**
     * @param Day $date_start
     */
    private function setStartDate(Day $date_start)
    {
        /** @var Carbon $week_start */
        $week_start = $date_start->startOfWeek();

        $this->date_start = new Day($week_start->year, $week_start->month, $week_start->day, $week_start->timezone);
    }

    /**
     * @return Day
     */
    public function getStartDate()
    {
        return $this->date_start;
    }

    /**
     * @param $current_month
     */
    private function setCurrentMonth($current_month)
    {
        if ($current_month === null) {
            $this->current_month = $this->date_start->month;
        } else {
            $this->current_month = $current_month;
        }
    }

    /**
     * @return bool|mixed
     */
    public function getCurrentMonth()
    {
        return $this->current_month;
    }

    /**
     * Generates a week from the specified start date
     */
    private function generateWeek()
    {
        $date = clone $this->date_start;
        for ($day = 1; $day < Carbon::DAYS_PER_WEEK; $day++) {
            $curr_day = clone $date->addDays(1);
            $this->addDay($curr_day);
        }
    }

    /**
     * Adds a day to the days property
     * Goes through a check first
     *
     * @param Day $day
     */
    private function addDay(Day $day)
    {
        $day->setBlankDay(! $this->currentMonthDay($day));

        $this->days[] = $day;
    }

    /**
     * @return array
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Determines whether a day is part of the currently
     * selected month or not
     *
     * @param Day $day
     *
     * @return bool
     */
    public function currentMonthDay(Day $day)
    {
        return $day->month === $this->getCurrentMonth();
    }
}

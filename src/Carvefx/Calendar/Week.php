<?php

namespace Carvefx\Calendar;

use Carbon\Carbon;

class Week
{
    /**
     * @var \Carvefx\Calendar\Day
     */
    private $dateStart;

    /**
     * @var array
     */
    private $days = [];

    /**
     * @var int
     */
    private $currentMonth;

    /**
     * @param \Carvefx\Calendar\Day $dateStart
     * @param mixed $currentMonth
     */
    public function __construct(Day $dateStart, $currentMonth = null)
    {
        $this->setStartDate($dateStart);
        $this->setCurrentMonth($currentMonth);
        $this->addDay($this->dateStart);
        $this->generateWeek();
    }

    /**
     * @param \Carvefx\Calendar\Day $dateStart
     */
    private function setStartDate(Day $dateStart)
    {
        /** @var Carbon $startOfWeek */
        $startOfWeek = $dateStart->startOfWeek();

        $this->dateStart = new Day($startOfWeek->year, $startOfWeek->month, $startOfWeek->day, $startOfWeek->timezone);
    }

    /**
     * @return \Carvefx\Calendar\Day
     */
    public function getStartDate()
    {
        return $this->dateStart;
    }

    /**
     * @param $currentMonth
     */
    private function setCurrentMonth($currentMonth)
    {
        $this->currentMonth = $currentMonth === null ? $this->dateStart->month : $currentMonth;
    }

    /**
     * @return null|int
     */
    public function getCurrentMonth()
    {
        return $this->currentMonth;
    }

    /**
     * Generates a week from the specified start date
     */
    private function generateWeek()
    {
        $date = clone $this->dateStart;
        for ($day = 1; $day < Carbon::DAYS_PER_WEEK; $day++) {
            $currDay = clone $date->addDays(1);
            $this->addDay($currDay);
        }
    }

    /**
     * Adds a day to the days property
     * Goes through a check first
     *
     * @param \Carvefx\Calendar\Day $day
     */
    private function addDay(Day $day)
    {
        $day->setBlankDay(! $this->currentMonthDay($day));

        $this->days[] = $day;
    }

    /**
     * @return \Carvefx\Calendar\Day[]
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Determines whether a day is part of the currently
     * selected month or not
     *
     * @param \Carvefx\Calendar\Day $day
     *
     * @return bool
     */
    public function currentMonthDay(Day $day)
    {
        return $day->month === $this->getCurrentMonth();
    }
}

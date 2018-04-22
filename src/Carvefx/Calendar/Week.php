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

    public function __construct(Day $dateStart, int $currentMonth = null)
    {
        $this->setStartDate($dateStart);
        $this->setCurrentMonth($currentMonth);
        $this->addDay($this->dateStart);
        $this->generateWeek();
    }

    private function setStartDate(Day $dateStart): void
    {
        $startOfWeek = $dateStart->startOfWeek();

        $this->dateStart = new Day($startOfWeek->year, $startOfWeek->month, $startOfWeek->day, $startOfWeek->timezone);
    }

    public function getStartDate(): Day
    {
        return $this->dateStart;
    }

    private function setCurrentMonth(int $currentMonth = null): void
    {
        $this->currentMonth = $currentMonth === null ? $this->dateStart->month : $currentMonth;
    }

    public function getCurrentMonth(): ?int
    {
        return $this->currentMonth;
    }

    /**
     * Generates a week from the specified start date
     */
    private function generateWeek(): void
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
     */
    private function addDay(Day $day): void
    {
        $day->setBlankDay(! $this->currentMonthDay($day));

        $this->days[] = $day;
    }

    /**
     * @return \Carvefx\Calendar\Day[]
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * Determines whether a day is part of the currently
     * selected month or not
     */
    public function currentMonthDay(Day $day): bool
    {
        return $day->month === $this->getCurrentMonth();
    }
}

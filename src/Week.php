<?php

namespace Calendar;

use Carbon\CarbonInterface;

class Week
{
    /**
     * @var \Calendar\Day
     */
    private $dateStart;

    /**
     * @var int
     */
    private $currentMonth;

    /**
     * @var int
     */
    private $weekStart = CarbonInterface::MONDAY;

    /**
     * @var array
     */
    private $days = [];

    public function __construct(Day $dateStart, int $currentMonth = null, int $weekStart = CarbonInterface::MONDAY)
    {
        $this->setWeekStart($weekStart);
        $this->setStartDate($dateStart);
        $this->setCurrentMonth($currentMonth);
        $this->addDay($this->dateStart);
        $this->generateWeek();
    }

    private function setStartDate(Day $dateStart): void
    {
        $startOfWeek = $dateStart->startOfWeek($this->getWeekStart());

        $this->dateStart = new Day($startOfWeek->year, $startOfWeek->month, $startOfWeek->day, $startOfWeek->timezone);
    }

    public function getStartDate(): Day
    {
        return $this->dateStart;
    }

    private function setCurrentMonth(int $currentMonth = null): void
    {
        $this->currentMonth = $currentMonth ?? $this->dateStart->month;
    }

    public function getCurrentMonth(): ?int
    {
        return $this->currentMonth;
    }

    public function setWeekStart(int $weekStart): void
    {
        $this->weekStart = $weekStart;
    }

    public function getWeekStart(): int
    {
        return $this->weekStart;
    }

    /**
     * Generates a week from the specified start date
     */
    private function generateWeek(): void
    {
        $date = clone $this->dateStart;
        for ($day = 1; $day < CarbonInterface::DAYS_PER_WEEK; $day++) {
            /** @var \Calendar\Day $date */
            $date = $date->addDay();
            $this->addDay(clone $date);
        }
    }

    /**
     * Adds a day to the days property
     * Goes through a check first
     *
     * @param  \Calendar\Day $day
     */
    private function addDay(Day $day): void
    {
        $day->setBlankDay(! $this->currentMonthDay($day));

        $this->days[] = $day;
    }

    /**
     * @return \Calendar\Day[]
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * Determines whether a day is part of the currently
     * selected month or not
     *
     * @param  \Calendar\Day $day
     *
     * @return bool
     */
    public function currentMonthDay(Day $day): bool
    {
        return $day->month === $this->getCurrentMonth();
    }
}

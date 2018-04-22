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

    public function __construct(int $year, int $month, $timezone = 'UTC')
    {
        $this->setYear($year);
        $this->setMonth($month);
        $this->setTimezone($timezone);
    }

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param \DateTimeZone|string $timezone
     *
     * @throws \InvalidArgumentException
     */
    public function setTimezone($timezone): void
    {
        if (! ($timezone instanceof DateTimeZone || is_string($timezone))) {
            throw new \InvalidArgumentException('setTimezone requires a DateTimeZone instance or a timezone string');
        }

        $this->timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
    }

    public function getTimezone(): DateTimeZone
    {
        return $this->timezone;
    }

    public function setVariableWeeks(bool $variableWeeks)
    {
        $this->variableWeeks = $variableWeeks;
    }

    public function isVariableWeeks(): bool
    {
        return $this->variableWeeks;
    }

    protected function getWeekCount(Day $firstDay): int
    {
        $startOfWeek = Carbon::getWeekStartsAt();

        return ceil(((($firstDay->dayOfWeek - $startOfWeek + 7) % 7) + $firstDay->daysInMonth) / 7);
    }

    public function getFirstDay(): Day
    {
        return new Day($this->year, $this->month, 1, $this->timezone);
    }

    public function getLastDay(): Carbon
    {
        $start = $this->getFirstDay();

        $endOfMonth = $start->endOfMonth()->startOfDay();

        return $this->getTimezone()->getName() === 'UTC' ? $endOfMonth : $endOfMonth->hour(5);
    }

    /**
     * @return \Carvefx\Calendar\Week[]
     */
    public function getWeeks(): array
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

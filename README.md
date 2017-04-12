[![Code Climate](https://codeclimate.com/github/carvefx/calendar.png)](https://codeclimate.com/github/carvefx/calendar)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/21f07ee7-f608-4a31-ae0e-d214dd962e4a/mini.png)](https://insight.sensiolabs.com/projects/21f07ee7-f608-4a31-ae0e-d214dd962e4a)
[![Build Status](https://travis-ci.org/carvefx/calendar.svg?branch=master)](https://travis-ci.org/carvefx/calendar)

calendar
========

Calendar Library written in PHP

## Usage

```php
require('vendor/autoload.php');

use Carvefx\Calendar\Calendar;

$calendar = new Calendar(2014, 8);

foreach($calendar->getWeeks() as $week) {
    foreach($week->getDays() as $day) {
        $day->toDateString(); // 2014-07-27
        $day->isBlankDay(); // true
    }
}
```

## Documentation

_Calendar_ comes with 3 main classes: 

* `\Carvefx\Calendar\Calendar`: represents a display of the current month, *including* the blank days that belong to the previous or next months
* `\Carvefx\Calendar\Week`: represents 7 days, regardless of the month it belongs to
* `\Carvefx\Calendar\Day`: represents a single day and wraps around the \Carbon\Carbon class

By default, the calendar will start on Monday. You can set which day of the week your calendar starts on by using Carbon's `setWeekStartsAt` and `setWeekEndsAt` methods:

```php
\Carbon\Carbon::setWeekStartsAt(Carbon::SUNDAY);
\Carbon\Carbon::setWeekEndsAt(Carbon::SATURDAY);

$calendar = new Calendar(2014, 8); // First day of the week is now Sunday
```

### `Calendar` API

```php
// Create a new Calendar object
$calendar = new Calendar(2014, 8);
// Or create a new Calendar object with a specific timezone
$calendar = new Calendar(2014, 8, 'America/Chicago');
$calendar = new Calendar(2014, 8, new DateTimeZone('America/Chicago'));

$calendar->setYear(2015);
$calendar->setMonth(7);
$calendar->setTimezone($timezone); // where $timezone is either a bare timezone string, or a DateTimeZone object
$calendar->getTimezone(); // returns the currently set timezone of the calendar
$calendar->getFirstDay(); // returns the first day of the month
$calendar->getLastDay(); // returns the last day of tyhe month
$calendar->getWeeks() // results in a number of Week objects
```

### `Week` API

```php
$first_day = new Day(2014, 07, 01);
$week = new Week($first_day); // constructor requires the day the week starts at
$week->getDays(); // will result in an array containing 7 Day objects
```

### `Day` API

```php
$day = new Day(2014, 07, 01);
$day->setBlankDay(false);
$day->isBlankDay(); // returns a bool value, shows whether the current day is part of the current month

// Any of the \Carbon\Carbon methods work
$day->getDateString(); // 2014-07-01
$day->month; // 7
```

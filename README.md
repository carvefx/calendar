[![Code Climate](https://codeclimate.com/github/kmdwebdesigns/calendar.png)](https://codeclimate.com/github/kmdwebdesigns/calendar)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/004f9246-a92c-479a-a0e0-4564fe43eaa5/mini.png)](https://insight.sensiolabs.com/projects/004f9246-a92c-479a-a0e0-4564fe43eaa5)
[![Build Status](https://travis-ci.org/kmdwebdesigns/calendar.svg?branch=master)](https://travis-ci.org/kmdwebdesigns/calendar)

calendar
========

Calendar Library written in PHP

## Installation

Add the following above the `require` block in your `composer.json`:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/kmdwebdesigns/calendar"
    }
],
```

Then run the following in your terminal:

```bash
$ composer require "carvefx/calendar:^1.1"
```

Or, add `"carvefx/calendar": "^1.1"` to your `composer.json` and then run `composer update` in your terminal.

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

Also by default, the calendar will display 6 weeks no matter how many weeks the actual month needs. This can be configured with the `setVariableWeeks` method:

```php
$calendar = new Calendar(2017, 8);
$calendar->setVariableWeeks(true);

$calendar->getWeeks(); // returns 5 weeks, instead of the normal 6, because the last week contains all blank days
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
$calendar->getTimezone(); // returns the currently set timezone of the calendar as a DateTimeZone object
$calendar->getFirstDay(); // returns the first day of the month
$calendar->getLastDay(); // returns the last day of the month
$calendar->getWeeks() // returns an array of Week objects
```

### `Week` API

```php
$first_day = new Day(2014, 07, 01);
$week = new Week($first_day); // constructor requires the day the week starts at
$week->getDays(); // returns an array containing 7 Day objects
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

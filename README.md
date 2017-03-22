[![Code Climate](https://codeclimate.com/github/carvefx/calendar.png)](https://codeclimate.com/github/carvefx/calendar)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/21f07ee7-f608-4a31-ae0e-d214dd962e4a/mini.png)](https://insight.sensiolabs.com/projects/21f07ee7-f608-4a31-ae0e-d214dd962e4a)
[![Build Status](https://travis-ci.org/carvefx/calendar.svg?branch=master)](https://travis-ci.org/carvefx/calendar)

calendar
========

Calendar Library written in PHP

## Usage

```
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

* Calendar: represents a display of the current month, *including* the blank days that belong to the previous or next months
* Week: represents 7 days, regardless of the month it belongs to
* Day: represents a single day and wraps around the \Carbon\Carbon class

### Day API

```
$day = new Day(2014, 07, 01);
$day->setBlankDay(false);
$day->isBlankDay(); // returns a bool value, shows whether the current day is part of the current month

// Any of the \Carbon\Carbon methods work
$day->getDateString(); // 2014-07-01
$day->month; // 7
```

### Week API

```
$first_day = new Day(2014, 07, 01);
$week = new Week($first_day); // constructor requires the day the week starts at
$week->getDays(); // will result in an array containing 7 Day objects
```


### Calendar API

```
$calendar = new Calendar(2014, 8);
$calendar->setYear(2015);
$calendar->setMonth(7);
$calendar->getFirstDay(); // returns the first day of the month
$calendar->getLastDay(); // returns the last day of tyhe month
$calendar->getWeeks() // results in a number of Week objects
```

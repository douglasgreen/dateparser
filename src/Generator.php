<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser;

/*
-   NUMBER: Matches any number.

<datetime_expression> ::= <datetime_phrase>
    | <datetime>
    | <simple_time>
    | <time_phrase>
    | <recurring_date>
    | <recurring_time>

<optional_time> ::= <simple_time>
    | <time_phrase>
    | ""

<clock_time> ::= TIME
    | TIME "AM"
    | TIME "PM"
    | HOUR

<time_phrase> ::= "at" <clock_time>
    | "at" <time_of_day>
    | "in" NUMBER <time_unit>
    | "in" <time_of_day>

<datetime_phrase> :== "on" <datetime>
    | "in" NUMBER <day_unit> <optional_time>

<datetime> ::= <simple_date> <optional_time>

<simple_time> ::= <clock_time>
    | <period_part> <time_of_day>
    | <period_part> <time_unit>
    | <sequence> <time_of_day>
    | <sequence> <time_unit>

<simple_date> ::= DATE
    | <day_of_week>
    | <month> NUMBER
    | <month> ORDINAL
    | <period_part> <month>
    | NUMBER <day_unit> "ago"
    | NUMBER <day_unit> <before_or_after> <simple_date>
    | ORDINAL <day_of_week>
    | ORDINAL <month>
    | ORDINAL
    | <relative_day>
    | <sequence> <day_of_week>
    | <sequence> <day_unit>
    | <sequence> <month>

<recurring_time> ::= <optional_frequency> <recurring_time_unit>
    | <repeater> <time_of_day>

<recurring_date> ::= <optional_frequency> <recurring_day_unit>
    | <plural_day_of_week>
    | <plural_month>
    | <repeater> <day_of_week>
    | <repeater> NUMBER <plural_day_of_week>
    | <repeater> NUMBER <plural_month>
    | <repeater> NUMBER <day_unit> <starting_or_ending> <simple_date>
    | <repeater> ORDINAL
    | <repeater> ORDINAL <day_of_week>
    | <repeater> ORDINAL <month>
    | <repeater> ORDINAL ORDINAL
    | <repeater> <day_unit>
    | <repeater> <day_unit> "for" NUMBER <day_unit>
    | <repeater> <day_unit> "from" <simple_date> "until" <simple_date>
    | <repeater> <day_unit> <simple_date>

<repeater> :== "every"
    | "every" NUMBER
    | "every" ORDINAL
    | "every" "other"

<before_or_after> :== "before" | "after"

<start_or_end> :== "start" | "end"

<starting_or_ending> :== "starting"
    | "ending"
    | "until"

<sequence> :== "last"
    | "this"
    | "next"

<optional_sequence> :== "sequence" | ""

<time_of_day> :== "morning"
    | "noon"
    | "afternoon"
    | "evening"
    | "night"
    | "midnight"

<relative_day> :== "yesterday"
    | "today"
    | "tomorrow"

<time_unit> ::= "second" | "seconds"
    | "minute" | "minutes"
    | "hour" | "hours"

<day_unit> ::= "day" | "days"
    | "week" | "weeks"
    | "month" | "months"
    | "quarter" | "quarters"
    | "year" | "years"

<frequency> ::= "once"
    | "twice"
    | NUMBER "times"

<optional_frequency> ::= <frequency> | ""

<recurring_time_unit> ::= "secondly"
    | "minutely"
    | "hourly"

<recurring_day_unit> ::= "daily"
    | "weekly"
    | "monthly"
    | "quarterly"
    | "yearly"
    | "annually"

<day_of_week> ::= "Monday" | "Mon"
    | "Tuesday" | "Tue"
    | "Wednesday" | "Wed"
    | "Thursday" | "Thu"
    | "Friday" | "Fri"
    | "Saturday" | "Sat"
    | "Sunday" | "Sun"
    | "weekday"
    | "weekend"

<plural_day_of_week> :== "Mondays"
    | "Tuesdays"
    | "Wednesdays"
    | "Thursdays"
    | "Fridays"
    | "Saturdays"
    | "Sundays"
    | "weekdays"
    | "weekends"

<month> ::= "January" | "Jan"
    | "February" | "Feb"
    | "March" | "Mar"
    | "April" | "Apr"
    | "May"
    | "June" | "Jun"
    | "July" | "Jul"
    | "August" | "Aug"
    | "September" | "Sep"
    | "October" | "Oct"
    | "November" | "Nov"
    | "December" | "Dec"

<plural_month> ::= "Januaries" | "Januarys"
    | "Februaries" | "Februarys"
    | "Marches"
    | "Aprils"
    | "May"
    | "Junes"
    | "Julies" | "Julys"
    | "Augusts"
    | "Septembers"
    | "Octobers"
    | "Novembers"
    | "Decembers"

<period_part> :== "early"
    | "mid"
    | "late"

<optional_period_part> ::= <period_part> | ""
*/
class Generator
{
    /**
     * DATE: Matches various date formats (e.g., 01/02, 2023-01-02).
     */
    public function genDate(): string
    {
        $year = mt_rand(1900, 2099);
        $month = mt_rand(1, 12);
        if (in_array($month, [4, 6, 9, 11], true)) {
            $days = 30;
        } elseif ($month === 2) {
            $days = 28;
        } else {
            $days = 31;
        }

        $day = mt_rand(1, $days);

        if (mt_rand(0, 1) === 0 && $year >= 2000) {
            $year %= 2000;
        }

        if (mt_rand(0, 1) === 0) {
            $month = sprintf('%02d', $month);
        }

        if (mt_rand(0, 1) === 0) {
            $day = sprintf('%02d', $day);
        }

        $sep = mt_rand(0, 1) === 0 ? '/' : '-';

        $parts = mt_rand(0, 1) === 0 ? [$year, $month, $day] : [$month, $day];

        return implode($sep, $parts);
    }

    /**
     * HOUR: Matches hour with AM/PM (e.g., 6pm).
     */
    public function genHour(): string
    {
        $hour = mt_rand(1, 12);

        if (mt_rand(0, 1) === 0) {
            $hour .= 'am';
        } else {
            $hour .= 'pm';
        }

        return $hour;
    }

    /**
     * ORDINAL: Matches ordinal numbers (e.g., 1st, 2nd, 3rd, 4th).
     */
    public function genOrdinal(): string
    {
        $number = mt_rand(1, 100);

        if ($number % 10 === 1 && $number % 100 !== 11) {
            $suffix = 'st';
        } elseif ($number % 10 === 2 && $number % 100 !== 12) {
            $suffix = 'nd';
        } elseif ($number % 10 === 3 && $number % 100 !== 13) {
            $suffix = 'rd';
        } else {
            $suffix = 'th';
        }

        return $number . $suffix;
    }

    /**
     * TIME: Matches time in HH:MM format (e.g., 14:30) or HH:MM:SS.
     */
    public function genTime(): string
    {
        $hour = mt_rand(0, 23);
        $minute = sprintf('%02d', mt_rand(0, 59));
        $second = sprintf('%02d', mt_rand(0, 59));

        if (mt_rand(0, 1) === 0) {
            $hour = sprintf('%02d', $hour);
        }

        $parts = mt_rand(0, 1) === 0 ? [$hour, $minute, $second] : [$hour, $minute];

        return implode(':', $parts);
    }
}

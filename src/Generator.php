<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser;

/*
<datetime_expression> ::= <datetime_phrase>
    | <datetime>
    | <simple_time>
    | <time_phrase>
    | <recurring_date>
    | <recurring_time>

<optional_time> ::= <simple_time>
    | <time_phrase>
    | ""

<time_phrase> ::= "at" <clock_time>
    | "at" <time_of_day>
    | "in" ONE <time_unit>
    | "in" TWO_OR_MORE <plural_time_unit>
    | "in" <time_of_day>

<datetime_phrase> :== "on" <datetime>
    | "in" <day_unit_count> <optional_time>

<datetime> ::= <simple_date> <optional_time>

<day_unit_count> :== ONE <day_unit>
    | TWO_OR_MORE <plural_day_unit>

<simple_date> ::= DATE
    | <day_of_week>
    | <day_unit_count> "ago"
    | <day_unit_count> <before_or_after> <simple_date>
    | <month> ONE
    | <month> ORDINAL
    | <month> TWO_OR_MORE
    | ORDINAL
    | ORDINAL <day_of_week>
    | ORDINAL <month>
    | <period_part> <month>
    | <relative_day>
    | <sequence> <day_of_week>
    | <sequence> <day_unit>
    | <sequence> <month>

<recurring_time> ::= <optional_frequency> <recurring_time_unit>
    | <repeater> <time_of_day>

<recurring_date> ::= <date_repeat_specifier> <optional_date_repeat_limit>

date_repeat_specifier> ::= <optional_frequency> <recurring_day_unit>
    | <plural_day_of_week>
    | <plural_month>
    | <plural_repeater> <plural_day_of_week>
    | <plural_repeater> <plural_day_unit>
    | <plural_repeater> <plural_month>
    | <repeater> <day_of_week>
    | <repeater> <day_unit>
    | <repeater> <month>
    | <repeater> ORDINAL

<date_repeat_limit> :== <starting_or_ending> <simple_date>
    | "for" <day_unit_count>
    | "from" <simple_date> "until" <simple_date>

<optional_date_repeat_limit> :== <date_repeat_limit> | ""
*/

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
class Generator
{
    /**
     * @return list<string>
     */
    public function collect(string $methodName, int $times = 10): array
    {
        $results = [];

        for ($i = 0; $i < $times; ++$i) {
            $result = $this->{$methodName}();
            $results[] = $result;
        }

        $results = array_unique($results);
        natsort($results);

        return $results;
    }

    /**
     * <before_or_after> :== "before" | "after"
     */
    public function genBeforeOrAfter(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0: return 'before';
            case 1: return 'after';
        }
    }

    /**
     * <clock_time> ::= TIME
     *     | TIME "AM"
     *     | TIME "PM"
     *     | HOUR
     */
    public function genClockTime(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0: return $this->genTime();
            case 1: return $this->genTime() . ' AM';
            case 2: return $this->genTime() . ' PM';
            case 3: return $this->genHour();
        }
    }

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
     * <day_of_week> ::= "Monday" | "Mon"
     *     | "Tuesday" | "Tue"
     *     | "Wednesday" | "Wed"
     *     | "Thursday" | "Thu"
     *     | "Friday" | "Fri"
     *     | "Saturday" | "Sat"
     *     | "Sunday" | "Sun"
     *     | "weekday"
     *     | "weekend"
     */
    public function genDayOfWeek(): string
    {
        $type = mt_rand(0, 15);
        switch ($type) {
            case 0: return 'Monday';
            case 1: return 'Mon';
            case 2: return 'Tuesday';
            case 3: return 'Tue';
            case 4: return 'Wednesday';
            case 5: return 'Wed';
            case 6: return 'Thursday';
            case 7: return 'Thu';
            case 8: return 'Friday';
            case 9: return 'Fri';
            case 10: return 'Saturday';
            case 11: return 'Sat';
            case 12: return 'Sunday';
            case 13: return 'Sun';
            case 14: return 'weekday';
            case 15: return 'weekend';
        }
    }

    /**
     * <day_unit> ::= "day"
     *     | "week"
     *     | "month"
     *     | "quarter"
     *     | "year"
     */
    public function genDayUnit(): string
    {
        $type = mt_rand(0, 4);
        switch ($type) {
            case 0: return 'day';
            case 1: return 'week';
            case 2: return 'month';
            case 3: return 'quarter';
            case 4: return 'year';
        }
    }

    /**
     * <frequency> ::= "once"
     *     | "twice"
     *     | ONE "time"
     *     | TWO_OR_MORE "times"
     */
    public function genFrequency(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0: return 'once';
            case 1: return 'twice';
            case 2: return $this->genOne() . ' time';
            case 2: return $this->genTwoOrMore() . ' times';
        }
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
     * <month> ::= "January" | "Jan"
     *     | "February" | "Feb"
     *     | "March" | "Mar"
     *     | "April" | "Apr"
     *     | "May"
     *     | "June" | "Jun"
     *     | "July" | "Jul"
     *     | "August" | "Aug"
     *     | "September" | "Sep"
     *     | "October" | "Oct"
     *     | "November" | "Nov"
     *     | "December" | "Dec"
     */
    public function genMonth(): string
    {
        $type = mt_rand(0, 22);
        switch ($type) {
            case 0: return 'January';
            case 1: return 'Jan';
            case 2: return 'February';
            case 3: return 'Feb';
            case 4: return 'March';
            case 5: return 'Mar';
            case 6: return 'April';
            case 7: return 'Apr';
            case 8: return 'May';
            case 9: return 'June';
            case 10: return 'Jun';
            case 11: return 'July';
            case 12: return 'Jul';
            case 13: return 'August';
            case 14: return 'Aug';
            case 15: return 'September';
            case 16: return 'Sep';
            case 17: return 'October';
            case 18: return 'Oct';
            case 19: return 'November';
            case 20: return 'Nov';
            case 21: return 'December';
            case 22: return 'Dec';
        }
    }

    /**
     * ONE: Matches the number 1.
     */
    public function genOne(): string
    {
        return '1';
    }

    /**
     * <optional_frequency> ::= <frequency> | ""
     */
    public function genOptionalFrequency(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0: return $this->genFrequency();
            case 1: return '""';
        }
    }

    /**
     * <optional_period_part> ::= <period_part> | ""
     */
    public function genOptionalPeriodPart(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0: return $this->genPeriodPart();
            case 1: return '""';
        }
    }

    /**
     * <optional_sequence> :== "sequence" | ""
     */
    public function genOptionalSequence(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0: return $this->genSequence();
            case 1: return '""';
        }
    }

    /**
     * ORDINAL: Matches ordinal numbers (e.g., 1st, 2nd, 3rd, 4th).
     */
    public function genOrdinal(): string
    {
        $number = mt_rand(1, 99);

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
     * <period_part> :== "early"
     *     | "mid"
     *     | "middle"
     *     | "late"
     */
    public function genPeriodPart(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0: return 'early';
            case 1: return 'mid';
            case 2: return 'middle';
            case 3: return 'late';
        }
    }

    /**
     * <plural_day_of_week> :== "Mondays"
     *     | "Tuesdays"
     *     | "Wednesdays"
     *     | "Thursdays"
     *     | "Fridays"
     *     | "Saturdays"
     *     | "Sundays"
     *     | "weekdays"
     *     | "weekends"
     */
    public function genPluralDayOfWeek(): string
    {
        $type = mt_rand(0, 8);
        switch ($type) {
            case 0: return 'Mondays';
            case 1: return 'Tuesdays';
            case 2: return 'Wednesdays';
            case 3: return 'Thursdays';
            case 4: return 'Fridays';
            case 5: return 'Saturdays';
            case 6: return 'Sundays';
            case 7: return 'weekdays';
            case 8: return 'weekends';
        }
    }

    /**
     * <plural_day_unit> ::= "days"
     *     | "weeks"
     *     | "months"
     *     | "quarters"
     *     | "years"
     */
    public function genPluralDayUnit(): string
    {
        $type = mt_rand(0, 4);
        switch ($type) {
            case 0: return 'days';
            case 1: return 'weeks';
            case 2: return 'months';
            case 3: return 'quarters';
            case 4: return 'years';
        }
    }

    /**
     * <plural_month> ::= "Januaries" | "Januarys"
     *     | "Februaries" | "Februarys"
     *     | "Marches"
     *     | "Aprils"
     *     | "May"
     *     | "Junes"
     *     | "Julies" | "Julys"
     *     | "Augusts"
     *     | "Septembers"
     *     | "Octobers"
     *     | "Novembers"
     *     | "Decembers"
     */
    public function genPluralMonth(): string
    {
        $type = mt_rand(0, 14);
        switch ($type) {
            case 0: return 'Januaries';
            case 1: return 'Januarys';
            case 2: return 'Februaries';
            case 3: return 'Februarys';
            case 4: return 'Marches';
            case 5: return 'Aprils';
            case 6: return 'May';
            case 7: return 'Junes';
            case 8: return 'Julies';
            case 9: return 'Julys';
            case 10: return 'Augusts';
            case 11: return 'Septembers';
            case 12: return 'Octobers';
            case 13: return 'Novembers';
            case 14: return 'Decembers';
        }
    }

    /**
     * <plural_repeater> :== "every" TWO_OR_MORE
     */
    public function genPluralRepeater(): string
    {
        return 'every ' . $this->genTwoOrMore();
    }

    /**
     * <time_unit> ::= "seconds"
     *     | "minutes"
     *     | "hours"
     */
    public function genPluralTimeUnit(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0: return 'seconds';
            case 1: return 'minutes';
            case 2: return 'hours';
        }
    }

    /**
     * <recurring_day_unit> ::= "daily"
     *     | "weekly"
     *     | "monthly"
     *     | "quarterly"
     *     | "yearly" | "annually"
     */
    public function genRecurringDayUnit(): string
    {
        $type = mt_rand(0, 5);
        switch ($type) {
            case 0: return 'daily';
            case 1: return 'weekly';
            case 2: return 'monthly';
            case 3: return 'quarterly';
            case 4: return 'yearly';
            case 5: return 'annually';
        }
    }

    /**
     * <recurring_time_unit> ::= "secondly"
     *     | "minutely"
     *     | "hourly"
     */
    public function genRecurringTimeUnit(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0: return 'secondly';
            case 1: return 'minutely';
            case 2: return 'hourly';
        }
    }

    /**
     * <relative_day> :== "yesterday"
     *     | "today"
     *     | "tomorrow"
     */
    public function genRelativeDay(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0: return 'yesterday';
            case 1: return 'today';
            case 2: return 'tomorrow';
        }
    }

    /**
     * <repeater> :== "every"
     *     | "every" ORDINAL
     *     | "every" "other"
     */
    public function genRepeater(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0: return 'every';
            case 1: return 'every ' . $this->genOrdinal();
            case 2: return 'every other';
        }
    }

    /**
     * <sequence> :== "last"
     *     | "this"
     *     | "next"
     */
    public function genSequence(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0: return 'last';
            case 1: return 'this';
            case 2: return 'next';
        }
    }

    /**
     * <simple_time> ::= <clock_time>
     *     | <period_part> <time_of_day>
     *     | <period_part> <time_unit>
     *     | <sequence> <time_of_day>
     *     | <sequence> <time_unit>
     */
    public function genSimpleTime(): string
    {
        $type = mt_rand(0, 4);
        switch ($type) {
            case 0: return $this->genClockTime();
            case 1: return $this->genPeriodPart() . ' ' . $this->genTimeOfDay();
            case 2: return $this->genPeriodPart() . ' ' . $this->genTimeUnit();
            case 3: return $this->genSequence() . ' ' . $this->genTimeOfDay();
            case 4: return $this->genSequence() . ' ' . $this->genTimeUnit();
        }
    }

    /**
     * <start_or_end> :== "start" | "end"
     */
    public function genStartOrEnd(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0: return 'start';
            case 1: return 'end';
        }
    }

    /**
     * <starting_or_ending> :== "starting"
     *     | "ending"
     *     | "since"
     *     | "until"
     */
    public function genStartingOrEnding(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0: return 'starting';
            case 1: return 'ending';
            case 2: return 'since';
            case 3: return 'until';
        }
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

    /**
     * <time_of_day> :== "morning"
     *     | "noon"
     *     | "afternoon"
     *     | "evening"
     *     | "night"
     *     | "midnight"
     */
    public function genTimeOfDay(): string
    {
        $type = mt_rand(0, 5);
        switch ($type) {
            case 0: return 'morning';
            case 1: return 'noon';
            case 2: return 'afternoon';
            case 3: return 'evening';
            case 4: return 'night';
            case 5: return 'midnight';
        }
    }

    /**
     * <time_unit> ::= "second"
     *     | "minute"
     *     | "hour"
     */
    public function genTimeUnit(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0: return 'second';
            case 1: return 'minute';
            case 2: return 'hour';
        }
    }

    /**
     * TWO_OR_MORE: Matches any number 2 or more.
     */
    public function genTwoOrMore(): string
    {
        return (string) mt_rand(2, 99);
    }
}

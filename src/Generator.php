<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser;

use DouglasGreen\Exceptions\ValueException;

/**
 * @todo check rand range and case numbers
 * @todo check that all functions and symbols are used
 * @todo develop logic of filling in missing parts of dates, like 'always on 1st'
 */

/**
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
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
     * <before_or_after> ::= "before" | "after"
     */
    public function genBeforeOrAfter(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return 'before';
            case 1:
                return 'after';
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
            case 0:
                return $this->genTime();
            case 1:
                return $this->genTime() . ' AM';
            case 2:
                return $this->genTime() . ' PM';
            case 3:
                return $this->genHour();
        }
    }

    /**
     * <complex_date> ::= <day_unit_count> "ago"
     *     | <day_unit_count> "from now"
     *     | ORDINAL <month>
     *     | <period_part> <day_of_week>
     *     | <period_part> <day_unit>
     *     | <period_part> <month>
     *     | <period_part> <relative_day>
     *     | <relative_day>
     *     | <sequence> <day_of_week>
     *     | <sequence> <day_unit>
     *     | <sequence> <month>
     */
    public function genComplexDate(): string
    {
        $type = mt_rand(0, 10);
        switch ($type) {
            case 0:
                return $this->genDayUnitCount() . ' ago';
            case 1:
                return $this->genDayUnitCount() . ' from now';
            case 2:
                return $this->genOrdinal() . ' ' . $this->genMonth();
            case 3:
                return $this->genPeriodPart() . ' ' . $this->genDayOfWeek();
            case 4:
                return $this->genPeriodPart() . ' ' . $this->genDayUnit();
            case 5:
                return $this->genPeriodPart() . ' ' . $this->genMonth();
            case 6:
                return $this->genPeriodPart() . ' ' . $this->genRelativeDay();
            case 7:
                return $this->genRelativeDay();
            case 8:
                return $this->genSequence() . ' ' . $this->genDayOfWeek();
            case 9:
                return $this->genSequence() . ' ' . $this->genDayUnit();
            case 10:
                return $this->genSequence() . ' ' . $this->genMonth();
        }
    }

    /**
     * DATE: Matches various date formats (e.g., 01/02, 2023-01-02).
     */
    public function genDate(): string
    {
        $year = mt_rand(1900, 2099);
        $month = mt_rand(1, 12);
        $daysInMonth = $this->getDaysInMonth($month);

        $day = mt_rand(1, $daysInMonth);

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
     * <date_expression> ::= <simple_date>
     *     | <complex_date>
     */
    public function genDateExpression(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genSimpleDate();
            case 1:
                return $this->genComplexDate();
        }
    }

    /**
     * <date_repeat_limit> ::= <starting_or_ending> <date_expression>
     *     | "for" <day_unit_count>
     *     | "between" <date_expression> "and" <date_expression>
     *     | "from" <date_expression> "until" <date_expression>
     */
    public function genDateRepeatLimit(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0:
                return $this->genStartingOrEnding() . ' ' . $this->genSimpleDate();
            case 1:
                return 'for ' . $this->genDayUnitCount();
            case 2:
                return 'between ' . $this->genSimpleDate() . ' and ' . $this->genSimpleDate();
            case 3:
                return 'from ' . $this->genSimpleDate() . ' until ' . $this->genSimpleDate();
        }
    }

    /**
     * date_repeat_specifier> ::= <optional_frequency> <recurring_day_unit>
     *     | <plural_day_of_week>
     *     | <plural_month>
     *     | <plural_repeater> <plural_day_of_week>
     *     | <plural_repeater> <plural_day_unit>
     *     | <plural_repeater> <plural_month>
     *     | <repeater> <day_of_week>
     *     | <repeater> <day_unit>
     *     | <repeater> <month>
     *     | <repeater> ORDINAL
     */
    public function genDateRepeatSpecifier(): string
    {
        $type = mt_rand(0, 9);
        switch ($type) {
            case 0:
                return $this->genOptionalFrequency() . ' ' . $this->genRecurringDayUnit();
            case 1:
                return $this->genPluralDayOfWeek();
            case 2:
                return $this->genPluralMonth();
            case 3:
                return $this->genPluralRepeater() . ' ' . $this->genPluralDayOfWeek();
            case 4:
                return $this->genPluralRepeater() . ' ' . $this->genPluralDayUnit();
            case 5:
                return $this->genPluralRepeater() . ' ' . $this->genPluralMonth();
            case 6:
                return $this->genRepeater() . ' ' . $this->genDayOfWeek();
            case 7:
                return $this->genRepeater() . ' ' . $this->genDayUnit();
            case 8:
                return $this->genRepeater() . ' ' . $this->genMonth();
            case 9:
                return $this->genRepeater() . ' ' . $this->genOrdinal();
        }
    }

    /**
     * <datetime> ::= <date_expression> <optional_time>
     */
    public function genDatetime(): string
    {
        return $this->genDateExpression() . ' ' . $this->genOptionalTime();
    }

    /**
     * <datetime_expression> ::= <datetime>
     *     | <datetime_phrase>
     *     | <recurring_date>
     *     | <recurring_time>
     *     | <relative_time_phrase>
     *     | <simple_time>
     *     | <time_phrase>
     */
    public function genDatetimeExpression(): string
    {
        $type = mt_rand(0, 6);
        switch ($type) {
            case 0:
                return $this->genDatetime();
            case 1:
                return $this->genDatetimePhrase();
            case 2:
                return $this->genRecurringDate();
            case 3:
                return $this->genRecurringTime();
            case 4:
                return $this->genRelativeTimePhrase();
            case 5:
                return $this->genSimpleTime();
            case 6:
                return $this->genTimePhrase();
        }
    }

    /**
     * <datetime_phrase> ::= "on" <simple_date> <optional_time>
     *     | "in" <day_unit_count> <optional_time>
     */
    public function genDatetimePhrase(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return 'on ' . $this->genSimpleDate() . ' ' . $this->genOptionalTime();
            case 1:
                return 'in ' . $this->genDayUnitCount() . ' ' . $this->genOptionalTime();
        }
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
            case 0:
                return 'Monday';
            case 1:
                return 'Mon';
            case 2:
                return 'Tuesday';
            case 3:
                return 'Tue';
            case 4:
                return 'Wednesday';
            case 5:
                return 'Wed';
            case 6:
                return 'Thursday';
            case 7:
                return 'Thu';
            case 8:
                return 'Friday';
            case 9:
                return 'Fri';
            case 10:
                return 'Saturday';
            case 11:
                return 'Sat';
            case 12:
                return 'Sunday';
            case 13:
                return 'Sun';
            case 14:
                return 'weekday';
            case 15:
                return 'weekend';
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
            case 0:
                return 'day';
            case 1:
                return 'week';
            case 2:
                return 'month';
            case 3:
                return 'quarter';
            case 4:
                return 'year';
        }
    }

    /**
     * <day_unit_count> ::= ONE <day_unit>
     *     | TWO_OR_MORE <plural_day_unit>
     */
    public function genDayUnitCount(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genOne() . ' ' . $this->genDayUnit();
            case 1:
                return $this->genTwoOrMore() . ' ' . $this->genPluralDayUnit();
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
            case 0:
                return 'once';
            case 1:
                return 'twice';
            case 2:
                return $this->genOne() . ' time';
            case 2:
                return $this->genTwoOrMore() . ' times';
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
            case 0:
                return 'January';
            case 1:
                return 'Jan';
            case 2:
                return 'February';
            case 3:
                return 'Feb';
            case 4:
                return 'March';
            case 5:
                return 'Mar';
            case 6:
                return 'April';
            case 7:
                return 'Apr';
            case 8:
                return 'May';
            case 9:
                return 'June';
            case 10:
                return 'Jun';
            case 11:
                return 'July';
            case 12:
                return 'Jul';
            case 13:
                return 'August';
            case 14:
                return 'Aug';
            case 15:
                return 'September';
            case 16:
                return 'Sep';
            case 17:
                return 'October';
            case 18:
                return 'Oct';
            case 19:
                return 'November';
            case 20:
                return 'Nov';
            case 21:
                return 'December';
            case 22:
                return 'Dec';
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
     * <optional_date_repeat_limit> ::= <date_repeat_limit> | ""
     */
    public function genOptionalDateRepeatLimit(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genDateRepeatLimit();
            case 1:
                return '""';
        }
    }

    /**
     * <optional_frequency> ::= <frequency> | ""
     */
    public function genOptionalFrequency(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genFrequency();
            case 1:
                return '""';
        }
    }

    /**
     * <optional_period_part> ::= <period_part> | ""
     */
    public function genOptionalPeriodPart(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genPeriodPart();
            case 1:
                return '""';
        }
    }

    /**
     * <optional_sequence> ::= "sequence" | ""
     */
    public function genOptionalSequence(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genSequence();
            case 1:
                return '""';
        }
    }

    /**
     * <optional_time> ::= <simple_time>
     *     | <time_phrase>
     *     | ""
     */
    public function genOptionalTime(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0:
                return $this->genSimpleTime();
            case 1:
                return $this->genTimePhrase();
            case 2:
                return '""';
        }
    }

    /**
     * ORDINAL: Matches ordinal numbers (e.g., 1st, 2nd, 3rd, 4th).
     */
    public function genOrdinal(int $max = 99): string
    {
        $number = mt_rand(1, $max);

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
     * <period_part> ::= "early"
     *     | "mid"
     *     | "middle"
     *     | "late"
     */
    public function genPeriodPart(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0:
                return 'early';
            case 1:
                return 'mid';
            case 2:
                return 'middle';
            case 3:
                return 'late';
        }
    }

    /**
     * <plural_day_of_week> ::= "Mondays"
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
            case 0:
                return 'Mondays';
            case 1:
                return 'Tuesdays';
            case 2:
                return 'Wednesdays';
            case 3:
                return 'Thursdays';
            case 4:
                return 'Fridays';
            case 5:
                return 'Saturdays';
            case 6:
                return 'Sundays';
            case 7:
                return 'weekdays';
            case 8:
                return 'weekends';
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
            case 0:
                return 'days';
            case 1:
                return 'weeks';
            case 2:
                return 'months';
            case 3:
                return 'quarters';
            case 4:
                return 'years';
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
            case 0:
                return 'Januaries';
            case 1:
                return 'Januarys';
            case 2:
                return 'Februaries';
            case 3:
                return 'Februarys';
            case 4:
                return 'Marches';
            case 5:
                return 'Aprils';
            case 6:
                return 'Mays';
            case 7:
                return 'Junes';
            case 8:
                return 'Julies';
            case 9:
                return 'Julys';
            case 10:
                return 'Augusts';
            case 11:
                return 'Septembers';
            case 12:
                return 'Octobers';
            case 13:
                return 'Novembers';
            case 14:
                return 'Decembers';
        }
    }

    /**
     * <plural_repeater> ::= "every" TWO_OR_MORE
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
            case 0:
                return 'seconds';
            case 1:
                return 'minutes';
            case 2:
                return 'hours';
        }
    }

    /**
     * <recurring_date> ::= <date_repeat_specifier> <optional_date_repeat_limit>
     */
    public function genRecurringDate(): string
    {
        return $this->genDateRepeatSpecifier() . ' ' . $this->genOptionalDateRepeatLimit();
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
            case 0:
                return 'daily';
            case 1:
                return 'weekly';
            case 2:
                return 'monthly';
            case 3:
                return 'quarterly';
            case 4:
                return 'yearly';
            case 5:
                return 'annually';
        }
    }

    /**
     * <recurring_time> ::= <optional_frequency> <recurring_time_unit>
     *     | <repeater> <time_of_day>
     */
    public function genRecurringTime(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genOptionalFrequency() . ' ' . $this->genRecurringTimeUnit();
            case 1:
                return $this->genRepeater() . ' ' . $this->genTimeOfDay();
        }
    }

    /**
     * <recurring_time_unit> ::= "per second"
     *     | "per minute"
     *     | "per hour"
     *     | "hourly"
     */
    public function genRecurringTimeUnit(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0:
                return 'per second';
            case 1:
                return 'per minute';
            case 2:
                return 'per hour';
            case 3:
                return 'hourly';
        }
    }

    /**
     * <relative_day> ::= "yesterday"
     *     | "today"
     *     | "tomorrow"
     */
    public function genRelativeDay(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0:
                return 'yesterday';
            case 1:
                return 'today';
            case 2:
                return 'tomorrow';
        }
    }

    /**
     * <relative_time_phrase> ::= "in" ONE <time_unit>
     *     | "in" TWO_OR_MORE <plural_time_unit>
     *     | <sequence> <time_of_day>
     *     | <sequence> <time_unit>
     */
    public function genRelativeTimePhrase(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0:
                return 'in ' . $this->genOne() . ' ' . $this->genTimeUnit();
            case 1:
                return 'in ' . $this->genTwoOrMore() . ' ' . $this->genPluralTimeUnit();
            case 2:
                return $this->genSequence() . ' ' . $this->genTimeOfDay();
            case 3:
                return $this->genSequence() . ' ' . $this->genTimeUnit();
        }
    }

    /**
     * <repeater> ::= "every"
     *     | "every" ORDINAL
     *     | "every" "other"
     */
    public function genRepeater(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0:
                return 'every';
            case 1:
                return 'every ' . $this->genOrdinal();
            case 2:
                return 'every other';
        }
    }

    /**
     * <sequence> ::= "last"
     *     | "this"
     *     | "next"
     */
    public function genSequence(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0:
                return 'last';
            case 1:
                return 'this';
            case 2:
                return 'next';
        }
    }

    /**
     * <simple_date> ::= DATE
     *     | <day_of_week>
     *     | <month> ONE
     *     | <month> ORDINAL
     *     | <month> TWO_OR_MORE
     *     | ORDINAL
     *     | ORDINAL <day_of_week>
     */
    public function genSimpleDate(): string
    {
        $type = mt_rand(0, 6);
        $month = $this->genMonth();
        $daysInMonth = $this->getDaysInMonth($month);
        switch ($type) {
            case 0:
                return $this->genDate();
            case 1:
                return $this->genDayOfWeek();
            case 2:
                return $month . ' ' . $this->genOne();
            case 3:
                return $month . ' ' . $this->genOrdinal($daysInMonth);
            case 4:
                return $month . ' ' . $this->genTwoOrMore($daysInMonth);
            case 5:
                return $this->genOrdinal(31);
            case 6:
                return $this->genOrdinal() . ' ' . $this->genDayOfWeek();
        }
    }

    /**
     * <simple_time> ::= <clock_time>
     *     | <period_part> <time_period_of_day>
     */
    public function genSimpleTime(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genClockTime();
            case 1:
                return $this->genPeriodPart() . ' ' . $this->genTimePeriodOfDay();
        }
    }

    /**
     * <start_or_end> ::= "start" | "end"
     */
    public function genStartOrEnd(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return 'start';
            case 1:
                return 'end';
        }
    }

    /**
     * <starting_or_ending> ::= "starting"
     *     | "ending"
     *     | "since"
     *     | "until"
     */
    public function genStartingOrEnding(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0:
                return 'starting';
            case 1:
                return 'ending';
            case 2:
                return 'since';
            case 3:
                return 'until';
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
     * <time_of_day> ::= <time_period_of_day>
     *     | <time_point_of_day>
     */
    public function genTimeOfDay(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return $this->genTimePeriodOfDay();
            case 1:
                return $this->genTimePointOfDay();
        }
    }

    /**
     * <time_period_of_day> ::= "morning"
     *     | "afternoon"
     *     | "evening"
     *     | "night"
     */
    public function genTimePeriodOfDay(): string
    {
        $type = mt_rand(0, 3);
        switch ($type) {
            case 0:
                return 'morning';
            case 1:
                return 'afternoon';
            case 2:
                return 'evening';
            case 3:
                return 'night';
        }
    }

    /**
     * <time_point_of_day> ::= "noon"
     *     | "midnight"
     */
    public function genTimePointOfDay(): string
    {
        $type = mt_rand(0, 1);
        switch ($type) {
            case 0:
                return 'noon';
            case 1:
                return 'midnight';
        }
    }

    /**
     * <time_phrase> ::= "at" <clock_time>
     *     | "at" <time_point_of_day>
     *     | "in" <time_period_of_day>
     */
    public function genTimePhrase(): string
    {
        $type = mt_rand(0, 2);
        switch ($type) {
            case 0:
                return 'at ' . $this->genClockTime();
            case 1:
                return 'at ' . $this->genTimePointOfDay();
            case 2:
                return 'in ' . $this->genTimePeriodOfDay();
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
            case 0:
                return 'second';
            case 1:
                return 'minute';
            case 2:
                return 'hour';
        }
    }

    /**
     * TWO_OR_MORE: Matches any number 2 or more.
     */
    public function genTwoOrMore(int $max = 99): string
    {
        return (string) mt_rand(2, $max);
    }

    /**
     * @throws ValueException
     */
    protected function getDaysInMonth(int|string $month): int
    {
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
            case 'January':
            case 'Jan':
            case 'March':
            case 'Mar':
            case 'May':
            case 'July':
            case 'Jul':
            case 'August':
            case 'Aug':
            case 'October':
            case 'Oct':
            case 'December':
            case 'Dec':
                return 31;
            case 2:
            case 'February':
            case 'Feb':
                return 29;
            case 4:
            case 6:
            case 9:
            case 11:
            case 'April':
            case 'Apr':
            case 'June':
            case 'Jun':
            case 'September':
            case 'Sep':
            case 'November':
            case 'Nov':
                return 30;
            default:
                throw new ValueException('Unknown month: ' . $month);
        }
    }
}

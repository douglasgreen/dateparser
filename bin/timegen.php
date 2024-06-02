#!/usr/bin/env php
<?php

declare(strict_types=1);

use DouglasGreen\DateParser\Generator;

require_once __DIR__ . '/../vendor/autoload.php';

$generator = new Generator();

/**
 * Tokens
 */
echo 'Dates:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genDate')) . PHP_EOL;
echo PHP_EOL;

echo 'Times:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genTime')) . PHP_EOL;
echo PHP_EOL;

echo 'Hours:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genHour')) . PHP_EOL;
echo PHP_EOL;

echo 'One:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genOne')) . PHP_EOL;
echo PHP_EOL;

echo 'Ordinal:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genOrdinal')) . PHP_EOL;
echo PHP_EOL;

echo 'Two or more:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genTwoOrMore')) . PHP_EOL;
echo PHP_EOL;

/**
 * Literals
 */
echo 'Before or after:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genBeforeOrAfter')) . PHP_EOL;
echo PHP_EOL;

echo 'Day of week:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genDayOfWeek')) . PHP_EOL;
echo PHP_EOL;

echo 'Day unit:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genDayUnit')) . PHP_EOL;
echo PHP_EOL;

echo 'Month:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genMonth')) . PHP_EOL;
echo PHP_EOL;

echo 'Optional period part:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genOptionalPeriodPart')) . PHP_EOL;
echo PHP_EOL;

echo 'Optional sequence:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genOptionalSequence')) . PHP_EOL;
echo PHP_EOL;

echo 'Period part:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genPeriodPart')) . PHP_EOL;
echo PHP_EOL;

echo 'Plural day of week:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genPluralDayOfWeek')) . PHP_EOL;
echo PHP_EOL;

echo 'Plural day unit:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genPluralDayUnit')) . PHP_EOL;
echo PHP_EOL;

echo 'Plural month:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genPluralMonth')) . PHP_EOL;
echo PHP_EOL;

echo 'Plural time unit:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genPluralTimeUnit')) . PHP_EOL;
echo PHP_EOL;

echo 'Recurring day unit:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genRecurringDayUnit')) . PHP_EOL;
echo PHP_EOL;

echo 'Recurring time unit:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genRecurringTimeUnit')) . PHP_EOL;
echo PHP_EOL;

echo 'Relative day:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genRelativeDay')) . PHP_EOL;
echo PHP_EOL;

echo 'Sequence:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genSequence')) . PHP_EOL;
echo PHP_EOL;

echo 'Start or end:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genStartOrEnd')) . PHP_EOL;
echo PHP_EOL;

echo 'Starting or ending:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genStartingOrEnding')) . PHP_EOL;
echo PHP_EOL;

echo 'Time of day:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genTimeOfDay')) . PHP_EOL;
echo PHP_EOL;

echo 'Time period of day:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genTimePeriodOfDay')) . PHP_EOL;
echo PHP_EOL;

echo 'Time point of day:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genTimePointOfDay')) . PHP_EOL;
echo PHP_EOL;

echo 'Time unit:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genTimeUnit')) . PHP_EOL;
echo PHP_EOL;

/**
 * Symbols
 */
echo 'Clock time:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genClockTime')) . PHP_EOL;
echo PHP_EOL;

echo 'Datetime:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genDatetime')) . PHP_EOL;
echo PHP_EOL;

echo 'Datetime phrase:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genDatetimePhrase')) . PHP_EOL;
echo PHP_EOL;

echo 'Day unit count:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genDayUnitCount')) . PHP_EOL;
echo PHP_EOL;

echo 'Frequency:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genFrequency')) . PHP_EOL;
echo PHP_EOL;

echo 'Optional frequency:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genOptionalFrequency')) . PHP_EOL;
echo PHP_EOL;

echo 'Optional time:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genOptionalTime')) . PHP_EOL;
echo PHP_EOL;

echo 'Plural repeater:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genPluralRepeater')) . PHP_EOL;
echo PHP_EOL;

echo 'Relative time phrase:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genRelativeTimePhrase')) . PHP_EOL;
echo PHP_EOL;

echo 'Repeater:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genRepeater')) . PHP_EOL;
echo PHP_EOL;

echo 'Simple date:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genSimpleDate', 20)) . PHP_EOL;
echo PHP_EOL;

echo 'Simple time:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genSimpleTime')) . PHP_EOL;
echo PHP_EOL;

echo 'Time phrase:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genTimePhrase')) . PHP_EOL;
echo PHP_EOL;

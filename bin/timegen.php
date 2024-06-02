#!/usr/bin/env php
<?php

declare(strict_types=1);

use DouglasGreen\DateParser\Generator;

require_once __DIR__ . '/../vendor/autoload.php';

$generator = new Generator();

echo 'Dates:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genDate')) . PHP_EOL;
echo PHP_EOL;

echo 'Times:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genTime')) . PHP_EOL;
echo PHP_EOL;

echo 'Hours:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genHour')) . PHP_EOL;
echo PHP_EOL;

echo 'Ordinal:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genOrdinal')) . PHP_EOL;
echo PHP_EOL;

echo 'Number:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genNumber')) . PHP_EOL;
echo PHP_EOL;

echo 'Before or after:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genBeforeOrAfter')) . PHP_EOL;
echo PHP_EOL;

echo 'Clock time:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genClockTime')) . PHP_EOL;
echo PHP_EOL;

echo 'Repeater:' . PHP_EOL;
echo implode(PHP_EOL, $generator->collect('genRepeater')) . PHP_EOL;
echo PHP_EOL;

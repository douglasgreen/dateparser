#!/usr/bin/env php
<?php

declare(strict_types=1);

use DouglasGreen\DateParser\Generator;

require_once __DIR__ . '/../vendor/autoload.php';

$generator = new Generator();

echo 'Dates:' . PHP_EOL;
for ($i = 0; $i < 10; ++$i) {
    echo $generator->genDate() . PHP_EOL;
}

echo PHP_EOL;

echo 'Times:' . PHP_EOL;
for ($i = 0; $i < 10; ++$i) {
    echo $generator->genTime() . PHP_EOL;
}

echo PHP_EOL;

echo 'Hours:' . PHP_EOL;
for ($i = 0; $i < 10; ++$i) {
    echo $generator->genHour() . PHP_EOL;
}

echo PHP_EOL;

echo 'Ordinal:' . PHP_EOL;
for ($i = 0; $i < 10; ++$i) {
    echo $generator->genOrdinal() . PHP_EOL;
}

echo PHP_EOL;

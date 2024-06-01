<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser;

class Token
{
    public function __construct(
        public string $type,
        public string $value
    ) {}
}

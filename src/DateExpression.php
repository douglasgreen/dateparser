<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser;

class DateExpression
{
    public function __construct(
        protected string $expression
    ) {}
}

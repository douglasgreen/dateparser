<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser;

class SyntaxTree
{
    /**
     * @param list<DateExpression> $dateExpressions
     */
    public function __construct(
        public array $dateExpressions
    ) {}
}

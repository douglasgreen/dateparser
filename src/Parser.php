<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser;

use DouglasGreen\Exceptions\ParseException;

class Parser
{
    protected ?Token $currentToken;

    public function __construct(
        protected Lexer $lexer,
        protected bool $isVerbose = false
    ) {
        $this->currentToken = $this->lexer->getNextToken();
    }

    public function parse(): SyntaxTree
    {
        $nodes = [];
        while ($this->currentToken instanceof Token) {
            $nodes[] = $this->parseDateExpression();
        }

        return new SyntaxTree($nodes);
    }

    protected function eat(string $tokenType, string|array $value = null): void
    {
        if (! $this->currentToken instanceof Token) {
            throw new ParseException('Out of tokens');
        }

        if ($this->currentToken->type !== $tokenType) {
            throw new ParseException('Unexpected token type: ' . json_encode($this->currentToken));
        }

        if ($value !== null) {
            if (is_string($value) && $this->currentToken->value !== $value) {
                throw new ParseException(
                    'Unexpected token literal: ' . json_encode($this->currentToken),
                );
            }

            if (is_array($value) && ! in_array($this->currentToken->value, $value, true)) {
                throw new ParseException(
                    'Unexpected token value: ' . json_encode($this->currentToken),
                );
            }
        }

        if ($this->isVerbose) {
            echo 'Parsing ' . $tokenType;
            if ($value !== null) {
                echo ': ' . $this->currentToken->value;
            }

            echo PHP_EOL;
        }

        $this->currentToken = $this->lexer->getNextToken();
    }

    protected function parseDateExpression(): DateExpression
    {
        if ($this->currentToken->type === 'date') {
            return $this->parseSimpleDate();
        }

        if ($this->currentToken->type === 'number' || $this->currentToken->value === 'in') {
            return $this->parseRelativeDate();
        }

        if ($this->currentToken->value === 'every') {
            return $this->parseRecurringDate();
        }

        throw new ParseException(
            'Unexpected token in date expression: ' . json_encode($this->currentToken),
        );
    }

    protected function parseSimpleDate(): SimpleDate
    {
        if ($this->currentToken->type === 'date') {
            $date = $this->currentToken->value;
            $this->eat('date');
            return new SimpleDate($date);
        }

        // Additional rules for simple dates (e.g., "13th", "Jan 13")
        throw new ParseException(
            'Unexpected token in simple date: ' . json_encode($this->currentToken),
        );
    }

    protected function parseRelativeDate(): RelativeDate
    {
        // Example: "in 2 hours", "3 days after 21 July"
        $relativeExpression = '';
        while (
            $this->currentToken->type === 'number' ||
            in_array(
                $this->currentToken->value,
                ['in', 'days', 'after', 'before', 'weeks', 'hours'],
                true,
            )
        ) {
            $relativeExpression .= $this->currentToken->value . ' ';
            $this->eat($this->currentToken->type);
        }

        return new RelativeDate(trim($relativeExpression));
    }

    protected function parseRecurringDate(): RecurringDate
    {
        // Example: "every 3 days", "every Monday", "every 2 weeks starting Jan 3"
        $recurringExpression = '';
        $this->eat('word', 'every'); // eat 'every'

        while ($this->currentToken instanceof Token) {
            $recurringExpression .= $this->currentToken->value . ' ';
            $this->eat($this->currentToken->type);
        }

        return new RecurringDate(trim($recurringExpression));
    }
}

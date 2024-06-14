<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser;

use DouglasGreen\Utility\Regex\Regex;

class Lexer
{
    /**
     * @var list<Token>
     */
    protected array $tokens = [];

    protected int $position = 0;

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(
        protected string $input,
        protected bool $isVerbose = false
    ) {
        $this->tokenize();
    }

    /**
     * @return ?Token
     */
    public function getNextToken()
    {
        if ($this->position < count($this->tokens)) {
            return $this->tokens[$this->position++];
        }

        return null;
    }

    /**
     * @return list<Token>
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function reset(): void
    {
        $this->position = 0;
    }

    /**
     * @throws RegexException
     */
    protected function tokenize(): void
    {
        // Define the pattern with the extended and dotall flags (allow comments and whitespace, and dot matches newlines)
        $pattern = '%
            (?P<word>\\b[a-zA-Z][a-zA-Z]*\\b) |

            (?P<date>\\b(?:\\d\\d(?:\\d\\d)?[/-])?\\d\\d?[/-]\\d\\d?) |

            # Time is HH:MM or HH:MM:SS to distinguish from other numbers.
            (?P<time>(?:0?[0-9]|1[0-9]|2[0-3]):[0-5]\\d(?::[0-5]\\d)?\\b) |

            (?P<hour>\\b(?:[1-9]|1[012])(?:am|pm)\\b) |

            (?P<ordinal>\\b[1-9]\\d*(st|nd|rd|th)\\b) |

            (?P<one>\\b1\\b) |

            (?P<twoOrMore>\\b(?:[2-9]|[1-9]\\d+)\\b)
        %isx';

        $result = preg_match_all($pattern, $this->input, $matches, PREG_SET_ORDER);

        if ($result === false) {
            throw new RegexException('Failure to match tokens');
        }

        foreach ($matches as $match) {
            // Trim the junk from the match array.
            $result = array_filter(
                $match,
                static fn($value, $key): bool => ! is_numeric($key) && strlen($value) > 0,
                ARRAY_FILTER_USE_BOTH,
            );

            if (isset($result['word'])) {
                // Skip articles and "of".
                if (in_array(strtolower($result['word']), ['a', 'an', 'the', 'of'], true)) {
                    continue;
                }

                $type = 'word';
            } elseif (isset($result['date'])) {
                $type = 'date';
            } elseif (isset($result['time'])) {
                $type = 'time';
            } elseif (isset($result['hour'])) {
                $type = 'hour';
            } elseif (isset($result['ordinal'])) {
                $type = 'ordinal';
            } elseif (isset($result['one'])) {
                $type = 'one';
            } elseif (isset($result['twoOrMore'])) {
                $type = 'twoOrMore';
            } else {
                throw new RegexException('Unrecognized token type: ' . json_encode($result));
            }

            $value = $result[$type];
            $this->tokens[] = new Token($type, $value);

            if ($this->isVerbose) {
                echo sprintf('Token: %s, Value: ', $type);
                $parts = Regex::split('/\R/', $value, -1, Regex::NO_EMPTY);
                echo implode(' ', $parts) . PHP_EOL;
            }
        }
    }
}

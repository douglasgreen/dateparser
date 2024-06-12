<?php

declare(strict_types=1);

namespace DouglasGreen\DateParser\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use DouglasGreen\DateParser\Lexer;
use DouglasGreen\DateParser\Token;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    public static function dateProvider(): \Iterator
    {
        yield ['01/02', [new Token('date', '01/02')]];
        yield ['1/02', [new Token('date', '1/02')]];
        yield ['13th', [new Token('ordinal', '13th')]];
        yield ['2023-01-02', [new Token('date', '2023-01-02')]];
        yield ['23-1-02', [new Token('date', '23-1-02')]];
        yield [
            '28 days after 21 July',
            [
                new Token('twoOrMore', '28'),
                new Token('word', 'days'),
                new Token('word', 'after'),
                new Token('twoOrMore', '21'),
                new Token('word', 'July'),
            ],
        ];
        yield [
            '3rd friday jan',
            [new Token('ordinal', '3rd'), new Token('word', 'friday'), new Token('word', 'jan')],
        ];
        yield [
            '3rd thu jul',
            [new Token('ordinal', '3rd'), new Token('word', 'thu'), new Token('word', 'jul')],
        ];
        yield [
            '60 days before Dec 31',
            [
                new Token('twoOrMore', '60'),
                new Token('word', 'days'),
                new Token('word', 'before'),
                new Token('word', 'Dec'),
                new Token('twoOrMore', '31'),
            ],
        ];
        yield ['6pm', [new Token('hour', '6pm')]];
        yield [
            '6 weeks before 21 Jul',
            [
                new Token('twoOrMore', '6'),
                new Token('word', 'weeks'),
                new Token('word', 'before'),
                new Token('twoOrMore', '21'),
                new Token('word', 'Jul'),
            ],
        ];
        yield [
            'after 11 days',
            [new Token('word', 'after'), new Token('twoOrMore', '11'), new Token('word', 'days')],
        ];
        yield ['daily', [new Token('word', 'daily')]];
        yield ['end of month', [new Token('word', 'end'), new Token('word', 'month')]];
        yield [
            'every 12 hours starting at 9pm',
            [
                new Token('word', 'every'),
                new Token('twoOrMore', '12'),
                new Token('word', 'hours'),
                new Token('word', 'starting'),
                new Token('word', 'at'),
                new Token('hour', '9pm'),
            ],
        ];
        yield ['every 13th', [new Token('word', 'every'), new Token('ordinal', '13th')]];
        yield [
            'every 14 jan, 14 apr, 15 jun, 15 sep',
            [
                new Token('word', 'every'),
                new Token('twoOrMore', '14'),
                new Token('word', 'jan'),
                new Token('twoOrMore', '14'),
                new Token('word', 'apr'),
                new Token('twoOrMore', '15'),
                new Token('word', 'jun'),
                new Token('twoOrMore', '15'),
                new Token('word', 'sep'),
            ],
        ];
        yield [
            'every 15th weekday',
            [
                new Token('word', 'every'),
                new Token('ordinal', '15th'),
                new Token('word', 'weekday'),
            ],
        ];
        yield [
            'every 1st wed jan',
            [
                new Token('word', 'every'),
                new Token('ordinal', '1st'),
                new Token('word', 'wed'),
                new Token('word', 'jan'),
            ],
        ];
        yield [
            'every 5, 15, 25',
            [
                new Token('word', 'every'),
                new Token('twoOrMore', '5'),
                new Token('twoOrMore', '15'),
                new Token('twoOrMore', '25'),
            ],
        ];
        yield [
            'every 2 months',
            [new Token('word', 'every'), new Token('twoOrMore', '2'), new Token('word', 'months')],
        ];
        yield [
            'every 3 days',
            [new Token('word', 'every'), new Token('twoOrMore', '3'), new Token('word', 'days')],
        ];
        yield [
            'every 3 hours',
            [new Token('word', 'every'), new Token('twoOrMore', '3'), new Token('word', 'hours')],
        ];
        yield [
            'every 3rd friday 8pm',
            [
                new Token('word', 'every'),
                new Token('ordinal', '3rd'),
                new Token('word', 'friday'),
                new Token('hour', '8pm'),
            ],
        ];
        yield [
            'every 3rd friday',
            [new Token('word', 'every'), new Token('ordinal', '3rd'), new Token('word', 'friday')],
        ];
        yield [
            'every 3 weekday',
            [new Token('word', 'every'), new Token('twoOrMore', '3'), new Token('word', 'weekday')],
        ];
        yield [
            'every 6 days',
            [new Token('word', 'every'), new Token('twoOrMore', '6'), new Token('word', 'days')],
        ];
        yield [
            'every 6 weeks at 09:00 starting jan 3',
            [
                new Token('word', 'every'),
                new Token('twoOrMore', '6'),
                new Token('word', 'weeks'),
                new Token('word', 'at'),
                new Token('time', '09:00'),
                new Token('word', 'starting'),
                new Token('word', 'jan'),
                new Token('twoOrMore', '3'),
            ],
        ];
        yield ['every afternoon', [new Token('word', 'every'), new Token('word', 'afternoon')]];
        yield [
            'every day ending aug 3',
            [
                new Token('word', 'every'),
                new Token('word', 'day'),
                new Token('word', 'ending'),
                new Token('word', 'aug'),
                new Token('twoOrMore', '3'),
            ],
        ];
        yield ['every day', [new Token('word', 'every'), new Token('word', 'day')]];
        yield [
            'every day for 3 weeks',
            [
                new Token('word', 'every'),
                new Token('word', 'day'),
                new Token('word', 'for'),
                new Token('twoOrMore', '3'),
                new Token('word', 'weeks'),
            ],
        ];
        yield [
            'every day from 11 May until 20 May',
            [
                new Token('word', 'every'),
                new Token('word', 'day'),
                new Token('word', 'from'),
                new Token('twoOrMore', '11'),
                new Token('word', 'May'),
                new Token('word', 'until'),
                new Token('twoOrMore', '20'),
                new Token('word', 'May'),
            ],
        ];
        yield [
            'every day starting aug 3',
            [
                new Token('word', 'every'),
                new Token('word', 'day'),
                new Token('word', 'starting'),
                new Token('word', 'aug'),
                new Token('twoOrMore', '3'),
            ],
        ];
        yield ['every evening', [new Token('word', 'every'), new Token('word', 'evening')]];
        yield [
            'every 1st weekday',
            [new Token('word', 'every'), new Token('ordinal', '1st'), new Token('word', 'weekday')],
        ];
        yield [
            'every fri at noon',
            [
                new Token('word', 'every'),
                new Token('word', 'fri'),
                new Token('word', 'at'),
                new Token('word', 'noon'),
            ],
        ];
        yield ['every hour', [new Token('word', 'every'), new Token('word', 'hour')]];
        yield [
            'every jan 13th',
            [new Token('word', 'every'), new Token('word', 'jan'), new Token('ordinal', '13th')],
        ];
        yield [
            'every last day',
            [new Token('word', 'every'), new Token('word', 'last'), new Token('word', 'day')],
        ];
        yield [
            'every last weekday',
            [new Token('word', 'every'), new Token('word', 'last'), new Token('word', 'weekday')],
        ];
        yield [
            'every monday, friday',
            [new Token('word', 'every'), new Token('word', 'monday'), new Token('word', 'friday')],
        ];
        yield [
            'every mon, fri at 20:00',
            [
                new Token('word', 'every'),
                new Token('word', 'mon'),
                new Token('word', 'fri'),
                new Token('word', 'at'),
                new Token('time', '20:00'),
            ],
        ];
        yield [
            'every mon, fri',
            [new Token('word', 'every'), new Token('word', 'mon'), new Token('word', 'fri')],
        ];
        yield [
            'every mon in the morning',
            [
                new Token('word', 'every'),
                new Token('word', 'mon'),
                new Token('word', 'in'),
                new Token('word', 'morning'),
            ],
        ];
        yield ['every month', [new Token('word', 'every'), new Token('word', 'month')]];
        yield ['every morning', [new Token('word', 'every'), new Token('word', 'morning')]];
        yield ['every night', [new Token('word', 'every'), new Token('word', 'night')]];
        yield [
            'every other day',
            [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'day')],
        ];
        yield [
            'every other fri',
            [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'fri')],
        ];
        yield [
            'every other month',
            [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'month')],
        ];
        yield [
            'every other week',
            [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'week')],
        ];
        yield [
            'every other year',
            [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'year')],
        ];
        yield ['every quarter', [new Token('word', 'every'), new Token('word', 'quarter')]];
        yield ['every weekday', [new Token('word', 'every'), new Token('word', 'weekday')]];
        yield ['every weekend', [new Token('word', 'every'), new Token('word', 'weekend')]];
        yield ['every week', [new Token('word', 'every'), new Token('word', 'week')]];
        yield ['every weekday', [new Token('word', 'every'), new Token('word', 'weekday')]];
        yield ['every year', [new Token('word', 'every'), new Token('word', 'year')]];
        yield ['1st weekday', [new Token('ordinal', '1st'), new Token('word', 'weekday')]];
        yield [
            'in 1 hour',
            [new Token('word', 'in'), new Token('one', '1'), new Token('word', 'hour')],
        ];
        yield [
            'in 2 hours',
            [new Token('word', 'in'), new Token('twoOrMore', '2'), new Token('word', 'hours')],
        ];
        yield [
            'in 3 weeks',
            [new Token('word', 'in'), new Token('twoOrMore', '3'), new Token('word', 'weeks')],
        ];
        yield [
            'in 6 days',
            [new Token('word', 'in'), new Token('twoOrMore', '6'), new Token('word', 'days')],
        ];
        yield ['in the afternoon', [new Token('word', 'in'), new Token('word', 'afternoon')]];
        yield [
            'in the evening every weds',
            [
                new Token('word', 'in'),
                new Token('word', 'evening'),
                new Token('word', 'every'),
                new Token('word', 'weds'),
            ],
        ];
        yield ['in the evening', [new Token('word', 'in'), new Token('word', 'evening')]];
        yield ['in the morning', [new Token('word', 'in'), new Token('word', 'morning')]];
        yield ['jan 13', [new Token('word', 'jan'), new Token('twoOrMore', '13')]];
        yield ['last weekday', [new Token('word', 'last'), new Token('word', 'weekday')]];
        yield [
            'later this week',
            [new Token('word', 'later'), new Token('word', 'this'), new Token('word', 'week')],
        ];
        yield ['mid January', [new Token('word', 'mid'), new Token('word', 'January')]];
        yield ['monthly', [new Token('word', 'monthly')]];
        yield ['next month', [new Token('word', 'next'), new Token('word', 'month')]];
        yield ['next Thursday', [new Token('word', 'next'), new Token('word', 'Thursday')]];
        yield ['next weekend', [new Token('word', 'next'), new Token('word', 'weekend')]];
        yield ['next week', [new Token('word', 'next'), new Token('word', 'week')]];
        yield ['next year', [new Token('word', 'next'), new Token('word', 'year')]];
        yield ['quarterly', [new Token('word', 'quarterly')]];
        yield ['this weekend', [new Token('word', 'this'), new Token('word', 'weekend')]];
        yield [
            'Thu at 6pm',
            [new Token('word', 'Thu'), new Token('word', 'at'), new Token('hour', '6pm')],
        ];
        yield ['Thursday', [new Token('word', 'Thursday')]];
        yield [
            'today at 11',
            [new Token('word', 'today'), new Token('word', 'at'), new Token('twoOrMore', '11')],
        ];
        yield ['today', [new Token('word', 'today')]];
        yield [
            'tomorrow afternoon',
            [new Token('word', 'tomorrow'), new Token('word', 'afternoon')],
        ];
        yield [
            'tomorrow at 15:00',
            [new Token('word', 'tomorrow'), new Token('word', 'at'), new Token('time', '15:00')],
        ];
        yield ['tomorrow evening', [new Token('word', 'tomorrow'), new Token('word', 'evening')]];
        yield ['tomorrow', [new Token('word', 'tomorrow')]];
        yield ['tomorrow morning', [new Token('word', 'tomorrow'), new Token('word', 'morning')]];
        yield ['tomorrow night', [new Token('word', 'tomorrow'), new Token('word', 'night')]];
        yield ['weekly', [new Token('word', 'weekly')]];
        yield ['yearly', [new Token('word', 'yearly')]];
    }

    #[DataProvider('dateProvider')]
    public function testDateParsing(string $input, array $expected): void
    {
        $lexer = new Lexer($input);
        $tokens = $lexer->getTokens();
        $this->assertEquals($expected, $tokens);
    }
}

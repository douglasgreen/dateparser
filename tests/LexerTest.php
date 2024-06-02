<?php

namespace DouglasGreen\DateParser\Tests;

use DouglasGreen\DateParser\Lexer;
use DouglasGreen\DateParser\Token;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    public static function dateProvider()
    {
        return [
            ['01/02', [new Token('date', '01/02')]],
            ['1/02', [new Token('date', '1/02')]],
            ['13th', [new Token('ordinal', '13th')]],
            ['2023-01-02', [new Token('date', '2023-01-02')]],
            ['23-1-02', [new Token('date', '23-1-02')]],
            ['28 days after 21 July', [new Token('number', '28'), new Token('word', 'days'), new Token('word', 'after'), new Token('number', '21'), new Token('word', 'July')]],
            ['3rd friday jan', [new Token('ordinal', '3rd'), new Token('word', 'friday'), new Token('word', 'jan')]],
            ['3rd thu jul', [new Token('ordinal', '3rd'), new Token('word', 'thu'), new Token('word', 'jul')]],
            ['60 days before Dec 31', [new Token('number', '60'), new Token('word', 'days'), new Token('word', 'before'), new Token('word', 'Dec'), new Token('number', '31')]],
            ['6pm', [new Token('hour', '6pm')]],
            ['6 weeks before 21 Jul', [new Token('number', '6'), new Token('word', 'weeks'), new Token('word', 'before'), new Token('number', '21'), new Token('word', 'Jul')]],
            ['after 11 days', [new Token('word', 'after'), new Token('number', '11'), new Token('word', 'days')]],
            ['daily', [new Token('word', 'daily')]],
            ['end of month', [new Token('word', 'end'), new Token('word', 'month')]],
            ['every 12 hours starting at 9pm', [new Token('word', 'every'), new Token('number', '12'), new Token('word', 'hours'), new Token('word', 'starting'), new Token('word', 'at'), new Token('hour', '9pm')]],
            ['every 13th', [new Token('word', 'every'), new Token('ordinal', '13th')]],
            ['every 14 jan, 14 apr, 15 jun, 15 sep', [new Token('word', 'every'), new Token('number', '14'), new Token('word', 'jan'), new Token('number', '14'), new Token('word', 'apr'), new Token('number', '15'), new Token('word', 'jun'), new Token('number', '15'), new Token('word', 'sep')]],
            ['every 15th weekday', [new Token('word', 'every'), new Token('ordinal', '15th'), new Token('word', 'weekday')]],
            ['every 1st wed jan', [new Token('word', 'every'), new Token('ordinal', '1st'), new Token('word', 'wed'), new Token('word', 'jan')]],
            ['every 5, 15, 25', [new Token('word', 'every'), new Token('number', '5'), new Token('number', '15'), new Token('number', '25')]],
            ['every 2 months', [new Token('word', 'every'), new Token('number', '2'), new Token('word', 'months')]],
            ['every 3 days', [new Token('word', 'every'), new Token('number', '3'), new Token('word', 'days')]],
            ['every 3 hours', [new Token('word', 'every'), new Token('number', '3'), new Token('word', 'hours')]],
            ['every 3rd friday 8pm', [new Token('word', 'every'), new Token('ordinal', '3rd'), new Token('word', 'friday'), new Token('hour', '8pm')]],
            ['every 3rd friday', [new Token('word', 'every'), new Token('ordinal', '3rd'), new Token('word', 'friday')]],
            ['every 3 weekday', [new Token('word', 'every'), new Token('number', '3'), new Token('word', 'weekday')]],
            ['every 6 days', [new Token('word', 'every'), new Token('number', '6'), new Token('word', 'days')]],
            ['every 6 weeks at 09:00 starting jan 3', [new Token('word', 'every'), new Token('number', '6'), new Token('word', 'weeks'), new Token('word', 'at'), new Token('time', '09:00'), new Token('word', 'starting'), new Token('word', 'jan'), new Token('number', '3')]],
            ['every afternoon', [new Token('word', 'every'), new Token('word', 'afternoon')]],
            ['every day ending aug 3', [new Token('word', 'every'), new Token('word', 'day'), new Token('word', 'ending'), new Token('word', 'aug'), new Token('number', '3')]],
            ['every day', [new Token('word', 'every'), new Token('word', 'day')]],
            ['every day for 3 weeks', [new Token('word', 'every'), new Token('word', 'day'), new Token('word', 'for'), new Token('number', '3'), new Token('word', 'weeks')]],
            ['every day from 11 May until 20 May', [new Token('word', 'every'), new Token('word', 'day'), new Token('word', 'from'), new Token('number', '11'), new Token('word', 'May'), new Token('word', 'until'), new Token('number', '20'), new Token('word', 'May')]],
            ['every day starting aug 3', [new Token('word', 'every'), new Token('word', 'day'), new Token('word', 'starting'), new Token('word', 'aug'), new Token('number', '3')]],
            ['every evening', [new Token('word', 'every'), new Token('word', 'evening')]],
            ['every 1st weekday', [new Token('word', 'every'), new Token('ordinal', '1st'), new Token('word', 'weekday')]],
            ['every fri at noon', [new Token('word', 'every'), new Token('word', 'fri'), new Token('word', 'at'), new Token('word', 'noon')]],
            ['every hour', [new Token('word', 'every'), new Token('word', 'hour')]],
            ['every jan 13th', [new Token('word', 'every'), new Token('word', 'jan'), new Token('ordinal', '13th')]],
            ['every last day', [new Token('word', 'every'), new Token('word', 'last'), new Token('word', 'day')]],
            ['every last weekday', [new Token('word', 'every'), new Token('word', 'last'), new Token('word', 'weekday')]],
            ['every monday, friday', [new Token('word', 'every'), new Token('word', 'monday'), new Token('word', 'friday')]],
            ['every mon, fri at 20:00', [new Token('word', 'every'), new Token('word', 'mon'), new Token('word', 'fri'), new Token('word', 'at'), new Token('time', '20:00')]],
            ['every mon, fri', [new Token('word', 'every'), new Token('word', 'mon'), new Token('word', 'fri')]],
            ['every mon in the morning', [new Token('word', 'every'), new Token('word', 'mon'), new Token('word', 'in'), new Token('word', 'morning')]],
            ['every month', [new Token('word', 'every'), new Token('word', 'month')]],
            ['every morning', [new Token('word', 'every'), new Token('word', 'morning')]],
            ['every night', [new Token('word', 'every'), new Token('word', 'night')]],
            ['every other day', [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'day')]],
            ['every other fri', [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'fri')]],
            ['every other month', [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'month')]],
            ['every other week', [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'week')]],
            ['every other year', [new Token('word', 'every'), new Token('word', 'other'), new Token('word', 'year')]],
            ['every quarter', [new Token('word', 'every'), new Token('word', 'quarter')]],
            ['every weekday', [new Token('word', 'every'), new Token('word', 'weekday')]],
            ['every weekend', [new Token('word', 'every'), new Token('word', 'weekend')]],
            ['every week', [new Token('word', 'every'), new Token('word', 'week')]],
            ['every weekday', [new Token('word', 'every'), new Token('word', 'weekday')]],
            ['every year', [new Token('word', 'every'), new Token('word', 'year')]],
            ['1st weekday', [new Token('ordinal', '1st'), new Token('word', 'weekday')]],
            ['in 2 hours', [new Token('word', 'in'), new Token('number', '2'), new Token('word', 'hours')]],
            ['in 3 weeks', [new Token('word', 'in'), new Token('number', '3'), new Token('word', 'weeks')]],
            ['in 6 days', [new Token('word', 'in'), new Token('number', '6'), new Token('word', 'days')]],
            ['in the afternoon', [new Token('word', 'in'), new Token('word', 'afternoon')]],
            ['in the evening every weds', [new Token('word', 'in'), new Token('word', 'evening'), new Token('word', 'every'), new Token('word', 'weds')]],
            ['in the evening', [new Token('word', 'in'), new Token('word', 'evening')]],
            ['in the morning', [new Token('word', 'in'), new Token('word', 'morning')]],
            ['jan 13', [new Token('word', 'jan'), new Token('number', '13')]],
            ['last weekday', [new Token('word', 'last'), new Token('word', 'weekday')]],
            ['later this week', [new Token('word', 'later'), new Token('word', 'this'), new Token('word', 'week')]],
            ['mid January', [new Token('word', 'mid'), new Token('word', 'January')]],
            ['monthly', [new Token('word', 'monthly')]],
            ['next month', [new Token('word', 'next'), new Token('word', 'month')]],
            ['next Thursday', [new Token('word', 'next'), new Token('word', 'Thursday')]],
            ['next weekend', [new Token('word', 'next'), new Token('word', 'weekend')]],
            ['next week', [new Token('word', 'next'), new Token('word', 'week')]],
            ['next year', [new Token('word', 'next'), new Token('word', 'year')]],
            ['quarterly', [new Token('word', 'quarterly')]],
            ['this weekend', [new Token('word', 'this'), new Token('word', 'weekend')]],
            ['Thu at 6pm', [new Token('word', 'Thu'), new Token('word', 'at'), new Token('hour', '6pm')]],
            ['Thursday', [new Token('word', 'Thursday')]],
            ['today at 11', [new Token('word', 'today'), new Token('word', 'at'), new Token('number', '11')]],
            ['today', [new Token('word', 'today')]],
            ['tomorrow afternoon', [new Token('word', 'tomorrow'), new Token('word', 'afternoon')]],
            ['tomorrow at 15:00', [new Token('word', 'tomorrow'), new Token('word', 'at'), new Token('time', '15:00')]],
            ['tomorrow evening', [new Token('word', 'tomorrow'), new Token('word', 'evening')]],
            ['tomorrow', [new Token('word', 'tomorrow')]],
            ['tomorrow morning', [new Token('word', 'tomorrow'), new Token('word', 'morning')]],
            ['tomorrow night', [new Token('word', 'tomorrow'), new Token('word', 'night')]],
            ['weekly', [new Token('word', 'weekly')]],
            ['yearly', [new Token('word', 'yearly')]],
        ];
    }

    /**
     * @dataProvider dateProvider
     */
    public function testDateParsing($input, $expected)
    {
        $lexer = new Lexer($input);
        $tokens = $lexer->getTokens();
        var_dump($tokens);
        $this->assertEquals($expected, $tokens);
    }
}

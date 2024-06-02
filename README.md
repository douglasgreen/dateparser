# dateparser

A PHP library to parse dates, times, and recurring date expressions

## Setup

See [Project Setup Guide](docs/setup_guide.md).

## Grammar Rules

Date expressions are described by this context-free grammar. It is broken down
into sections.

### Tokens

Tokens are returned by the lexer.

-   `WORD`: Matches any single word composed of alphabetic characters, used to
    match literals.
-   `DATE`: Matches various date formats (e.g., 01/02, 2023-01-02).
-   `TIME`: Matches time in HH:MM format (e.g., 14:30) or HH:MM:SS.
-   `HOUR`: Matches hour with AM/PM (e.g., 6pm).
-   `ORDINAL`: Matches ordinal numbers (e.g., 1st, 2nd, 3rd, 4th).
-   `ONE`: Matches the number 1.
-   `TWO_OR_MORE`: Matches any number 2 or more.

### Literals

Literals contain only literal strings.

```
<before_or_after> ::= "before" | "after"

<day_of_week> ::= "Monday" | "Mon"
    | "Tuesday" | "Tue"
    | "Wednesday" | "Wed"
    | "Thursday" | "Thu"
    | "Friday" | "Fri"
    | "Saturday" | "Sat"
    | "Sunday" | "Sun"
    | "weekday"
    | "weekend"

<day_unit> ::= "day"
    | "week"
    | "month"
    | "quarter"
    | "year"

<month> ::= "January" | "Jan"
    | "February" | "Feb"
    | "March" | "Mar"
    | "April" | "Apr"
    | "May"
    | "June" | "Jun"
    | "July" | "Jul"
    | "August" | "Aug"
    | "September" | "Sep"
    | "October" | "Oct"
    | "November" | "Nov"
    | "December" | "Dec"

<optional_period_part> ::= <period_part> | ""

<optional_sequence> ::= "sequence" | ""

<period_part> ::= "early"
    | "mid"
    | "middle"
    | "late"

<plural_day_of_week> ::= "Mondays"
    | "Tuesdays"
    | "Wednesdays"
    | "Thursdays"
    | "Fridays"
    | "Saturdays"
    | "Sundays"
    | "weekdays"
    | "weekends"

<plural_day_unit> ::= "days"
    | "weeks"
    | "months"
    | "quarters"
    | "years"

<plural_month> ::= "Januaries" | "Januarys"
    | "Februaries" | "Februarys"
    | "Marches"
    | "Aprils"
    | "Mays"
    | "Junes"
    | "Julies" | "Julys"
    | "Augusts"
    | "Septembers"
    | "Octobers"
    | "Novembers"
    | "Decembers"

<plural_time_unit> ::= "seconds"
    | "minutes"
    | "hours"

<recurring_day_unit> ::= "daily"
    | "weekly"
    | "monthly"
    | "quarterly"
    | "yearly" | "annually"

<recurring_time_unit> ::= "per second"
    | "per minute"
    | "per hour"
    | "hourly"

<relative_day> ::= "yesterday"
    | "today"
    | "tomorrow"

<sequence> ::= "last"
    | "this"
    | "next"

<start_or_end> ::= "start" | "end"

<starting_or_ending> ::= "starting"
    | "ending"
    | "since"
    | "until"

<time_of_day> ::= <time_period_of_day>
    | <time_point_of_day>

<time_period_of_day> ::= "morning"
    | "afternoon"
    | "evening"
    | "night"

<time_point_of_day> ::= "noon"
    | "midnight"

<time_unit> ::= "second"
    | "minute"
    | "hour"
```

### Symbols

```
<clock_time> ::= TIME
    | TIME "AM"
    | TIME "PM"
    | HOUR

<complex_date> ::= <day_unit_count> "ago"
    | <day_unit_count> "from now"
    | ORDINAL <month>
    | <period_part> <day_of_week>
    | <period_part> <day_unit>
    | <period_part> <month>
    | <period_part> <relative_day>
    | <relative_day>
    | <sequence> <day_of_week>
    | <sequence> <day_unit>
    | <sequence> <month>

<date_expression> ::= <simple_date>
    | <complex_date>

<date_repeat_limit> ::= <starting_or_ending> <date_expression>
    | "for" <day_unit_count>
    | "between" <date_expression> "and" <date_expression>
    | "from" <date_expression> "until" <date_expression>

<date_repeat_specifier> ::= <optional_frequency> <recurring_day_unit>
    | <plural_day_of_week>
    | <plural_month>
    | <plural_repeater> <plural_day_of_week>
    | <plural_repeater> <plural_day_unit>
    | <plural_repeater> <plural_month>
    | <repeater> <day_of_week>
    | <repeater> <day_unit>
    | <repeater> <month>
    | <repeater> ORDINAL

<datetime> ::= <date_expression> <optional_time>

<datetime_expression> ::= <datetime>
    | <datetime_phrase>
    | <recurring_date>
    | <recurring_time>
    | <relative_time_phrase>
    | <simple_time>
    | <time_phrase>

<datetime_phrase> ::= "on" <simple_date> <optional_time>
    | "in" <day_unit_count> <optional_time>

<day_unit_count> ::= ONE <day_unit>
    | TWO_OR_MORE <plural_day_unit>

<frequency> ::= "once"
    | "twice"
    | ONE "time"
    | TWO_OR_MORE "times"

<optional_date_repeat_limit> ::= <date_repeat_limit> | ""

<optional_frequency> ::= <frequency> | ""

<optional_time> ::= <simple_time>
    | <time_phrase>
    | ""

<plural_repeater> ::= "every" TWO_OR_MORE

<recurring_date> ::= <date_repeat_specifier> <optional_date_repeat_limit>

<recurring_time> ::= <optional_frequency> <recurring_time_unit>
    | <repeater> <time_of_day>

<relative_time_phrase> ::= "in" ONE <time_unit>
    | "in" TWO_OR_MORE <plural_time_unit>
    | <sequence> <time_of_day>
    | <sequence> <time_unit>

<repeater> ::= "every"
    | "every" ORDINAL
    | "every" "other"

<simple_date> ::= DATE
    | <day_of_week>
    | <month> ONE
    | <month> ORDINAL
    | <month> TWO_OR_MORE
    | ORDINAL
    | ORDINAL <day_of_week>

<simple_time> ::= <clock_time>
    | <period_part> <time_period_of_day>

<time_phrase> ::= "at" <clock_time>
    | "at" <time_point_of_day>
    | "in" <time_period_of_day>
```

### Notes

The lexer skips articles (a, an, and the) and the word "of" so you can write
dates in the form "the next month" and it will be interpreted as "next month".

Dates are always in year, month, day order. So 2024-12-01, 12-01, and 2024
January 1 are all valid but 2024-01-12, 01-12, and 1 January 2024 are not valid.

Any token named "date" only refers to dates and not times. Any token named
"time" only refers to times and not dates. Any token named "datetime" refers to
a date and/or a time.

Any token named "phrase" always starts with a literal preposition like "at,
"in", or "on".

TIME by itself is a 24-hour clock, of hours, minutes, and optionally seconds.
<clock_time> allows for the English clock usage of AM and PM. HOUR is just a
different way of writing whole hours as a combined word, like 8am but not
8:30am.

Instead of "semiweekly" use "twice weekly" and so on for the other time units.

Instead of "biweekly" use "every other week" and so on for the other time units.

Literal strings are case insensitive so you can write them with or without
capital letters, like AM or Am or am.

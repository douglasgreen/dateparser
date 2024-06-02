# dateparser

A PHP library to parse dates, times, and recurring date expressions

## Setup

See [Project Setup Guide](docs/setup_guide.md).

## Date Expressions

### Tokens

-   WORD: Matches any single word composed of alphabetic characters, used to
    match literals.
-   DATE: Matches various date formats (e.g., 01/02, 2023-01-02).
-   TIME: Matches time in HH:MM format (e.g., 14:30) or HH:MM:SS.
-   HOUR: Matches hour with AM/PM (e.g., 6pm).
-   ORDINAL: Matches ordinal numbers (e.g., 1st, 2nd, 3rd, 4th).
-   NUMBER: Matches any number.

### Grammar Rules

Date expressions are described by this context-free grammar.

```
<datetime_expression> ::= <datetime_phrase>
    | <datetime>
    | <simple_time>
    | <time_phrase>
    | <recurring_date>
    | <recurring_time>

<optional_time> ::= <simple_time>
    | <time_phrase>
    | ""

<clock_time> ::= TIME
    | TIME "AM"
    | TIME "PM"
    | HOUR

<time_phrase> ::= "at" <clock_time>
    | "at" <time_of_day>
    | "in" NUMBER <time_unit>
    | "in" <time_of_day>

<datetime_phrase> :== "on" <datetime>
    | "in" NUMBER <day_unit> <optional_time>

<datetime> ::= <simple_date> <optional_time>

<simple_time> ::= <clock_time>
    | <period_part> <time_of_day>
    | <period_part> <time_unit>
    | <sequence> <time_of_day>
    | <sequence> <time_unit>

<simple_date> ::= DATE
    | <day_of_week>
    | <month> NUMBER
    | <month> ORDINAL
    | <period_part> <month>
    | NUMBER <day_unit> "ago"
    | NUMBER <day_unit> <before_or_after> <simple_date>
    | ORDINAL <day_of_week>
    | ORDINAL <month>
    | ORDINAL
    | <relative_day>
    | <sequence> <day_of_week>
    | <sequence> <day_unit>
    | <sequence> <month>

<recurring_time> ::= <optional_frequency> <recurring_time_unit>
    | <repeater> <time_of_day>

<recurring_date> ::= <optional_frequency> <recurring_day_unit>
    | <plural_day_of_week>
    | <plural_month>
    | <repeater> <day_of_week>
    | <repeater> NUMBER <plural_day_of_week>
    | <repeater> NUMBER <plural_month>
    | <repeater> NUMBER <day_unit> <starting_or_ending> <simple_date>
    | <repeater> ORDINAL
    | <repeater> ORDINAL <day_of_week>
    | <repeater> ORDINAL <month>
    | <repeater> ORDINAL ORDINAL
    | <repeater> <day_unit>
    | <repeater> <day_unit> "for" NUMBER <day_unit>
    | <repeater> <day_unit> "from" <simple_date> "until" <simple_date>
    | <repeater> <day_unit> <simple_date>

<repeater> :== "every"
    | "every" NUMBER
    | "every" ORDINAL
    | "every" "other"

<before_or_after> :== "before" | "after"

<start_or_end> :== "start" | "end"

<starting_or_ending> :== "starting"
    | "ending"
    | "until"

<sequence> :== "last"
    | "this"
    | "next"

<optional_sequence> :== "sequence" | ""

<time_of_day> :== "morning"
    | "noon"
    | "afternoon"
    | "evening"
    | "night"
    | "midnight"

<relative_day> :== "yesterday"
    | "today"
    | "tomorrow"

<time_unit> ::= "second" | "seconds"
    | "minute" | "minutes"
    | "hour" | "hours"

<day_unit> ::= "day" | "days"
    | "week" | "weeks"
    | "month" | "months"
    | "quarter" | "quarters"
    | "year" | "years"

<frequency> ::= "once"
    | "twice"
    | NUMBER "times"

<optional_frequency> ::= <frequency> | ""

<recurring_time_unit> ::= "secondly"
    | "minutely"
    | "hourly"

<recurring_day_unit> ::= "daily"
    | "weekly"
    | "monthly"
    | "quarterly"
    | "yearly" | "annually"

<day_of_week> ::= "Monday" | "Mon"
    | "Tuesday" | "Tue"
    | "Wednesday" | "Wed"
    | "Thursday" | "Thu"
    | "Friday" | "Fri"
    | "Saturday" | "Sat"
    | "Sunday" | "Sun"
    | "weekday"
    | "weekend"

<plural_day_of_week> :== "Mondays"
    | "Tuesdays"
    | "Wednesdays"
    | "Thursdays"
    | "Fridays"
    | "Saturdays"
    | "Sundays"
    | "weekdays"
    | "weekends"

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

<plural_month> ::= "Januaries" | "Januarys"
    | "Februaries" | "Februarys"
    | "Marches"
    | "Aprils"
    | "May"
    | "Junes"
    | "Julies" | "Julys"
    | "Augusts"
    | "Septembers"
    | "Octobers"
    | "Novembers"
    | "Decembers"

<period_part> :== "early"
    | "mid"
    | "middle"
    | "late"

<optional_period_part> ::= <period_part> | ""
```

### Notes

The lexer skips articles (a, an, and the) and the word "of" so you can write
dates in the form "the 4th of July" and it will be interpreted as "4th July".

Dates are always in year, month, day order. So 2024-12-01, 12-01, and 2024
January 1 are all valid but 2024-01-12, 01-12, and 1 January 2024 are not valid.
However 1st January is allowed to mean January 1st.

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

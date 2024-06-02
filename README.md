# dateparser

A PHP library to parse dates, times, and recurring date expressions

## Setup

See [Project Setup Guide](docs/setup_guide.md).

## Date Expressions

### Tokens

-   word: Matches any single word composed of alphabetic characters.
-   day: Matches ordinal numbers (e.g., 1st, 2nd, 3rd, 4th).
-   date: Matches various date formats (e.g., 01/02, 2023-01-02).
-   time: Matches time in HH:MM format (e.g., 14:30).
-   hour: Matches hour with AM/PM (e.g., 6pm).
-   number: Matches any number.

### Grammar Rules

Date expressions are described by this context-free grammar.

```
<date_expression> ::= <simple_date>
                    | <simple_time>
                    | <relative_date>
                    | <recurring_date>

<simple_date> ::= "on" <date> <optional_time>
                | "on" <day> <month> <optional_time>
                | "on" <day> <optional_time>
                | "on" <month> <day> <optional_time>
                | "on" <month> <number> <optional_time>
                | "on" <number> <month> <optional_time>
                | "on" <day_of_week> <optional_time>

<optional_time> :== <simple_time> | ""

<simple_time> ::= "at" <hour>
                | "at" <time>

<relative_date> ::= "in" <number> <time_unit> <before_or_after> <simple_date>
                  | "in" <number> <time_unit>
                  | "mid" <month>
                  | "today"
                  | "tomorrow"
                  | <this_or_next> <time_unit>
                  | <this_or_next> <day_of_week>
                  | <this_or_next> <month>

<recurring_date> ::= <recurring_time_unit>
                   | "every" <number> <time_unit>
                   | "every" <time_unit>
                   | "every" <number> <day>
                   | "every" <day>
                   | "every" <time> "starting at" <hour>
                   | "every" <time> "at" <time>
                   | "every" <number> "weekday"
                   | "every" <word> "weekday"
                   | "every" <number> <word>
                   | "every" <number> "weekdays"
                   | "every" <number> <time_unit> <starting_or_ending> <simple_date>
                   | "every" <time_unit> <simple_date>
                   | "every" <time_unit> "for" <number> "weeks"
                   | "every" <time_unit> "from" <simple_date> "until" <simple_date>

<before_or_after> :== "before" | "after"

<start_or_end> :== "start" | "end"

<starting_or_ending> :== "starting" | "ending"

<this_or_next> :== "this" | "next"

<time_of_day> :== "morning"
                | "noon"
			    | "afternoon"
                | "evening"
                | "night"

<time_unit> ::= "hour" | "hours"
              | "day" | "days"
              | "week" | "weeks"
              | "month" | "months"
              | "quarter" | "quarters"
              | "year" | "years"

<recurring_time_unit> ::= "hourly"
                        | "daily"
                        | "weekly"
                        | "monthly"
                        | "quarterly"
                        | "yearly"

<day_of_week> ::= "Monday" | "Mon"
                | "Tuesday" | "Tue"
                | "Wednesday" | "Wed"
                | "Thursday" | "Thu"
                | "Friday" | "Fri"
                | "Saturday" | "Sat"
                | "Sunday" | "Sun"

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
```

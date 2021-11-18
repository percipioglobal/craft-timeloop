# Timeloop plugin for Craft CMS 3.x

This plugin creates repeating dates without complex inputs

![timeloop-banner-light (1)](https://user-images.githubusercontent.com/20947573/117322933-bcbca200-ae8e-11eb-834f-1a2aeba472b6.png)

## Requirements

This plugin requires Craft CMS 3.3.0 or later.

## Installation

To install the plugin, follow these instructions:

1. Open your terminal and go to your Craft project:

```
    cd/path/to/project
```

2. Tell Composer to load the plugin:

```
    composer require percipiolondon/craft-timeloop
```

3. In the Control Panel, go to Settings → Plugins and click the “Install” button.

## Timeloop Overview

The Timeloop plugin provides recurring dates based on a starting date and a regular loop period.

**Example**: Set a payment date for employees on the first of each month.
**Example**: Display the upcoming dance class from a dance school that repeats every Tuesday and Thursday.
**Example**: Display the next school board meeting, which takes place every third Friday of the month.

## Configuring the Timeloop field.

The following configuration options are available for the field:

- **Show Times**: When selected, this will allow a start time and end time for the recurring dates to be chosen. 

## Using Timeloop

### The Timeloop Model

#### Fetching dates (returned as DateTime objects)

Get the start date from the loop (this includes the time set in `loopStartTime`):

```
    {{ entry.timeloop.loopStartDate | date('Y-m-d\\TH:i:sP') }}
```

Get the end date from the loop (this includes the time set in `loopEndHour`):

```
    {{ entry.timeloop.loopEndDate | date('Y-m-d\\TH:i:sP') }}
```

Get the start time from the loop:

```
    {{ entry.timeloop.loopStartTime | date('H:i:s') }}
```

Get the end time from the loop:

```
    {{ entry.timeloop.loopEndTime | date('H:i:s') }}
```

Get an 'array' of dates between the chosen start and end date (DateTime objects):

```
    {% for date in entry.timeloop.dates %}
        {{ date | date('Y-m-d\\TH:i:sP') }}
    {% endfor %}
```

This generated set of dates takes all the field values into consideration (frequency, cycle and custom settings)


#### Upcoming Dates (returned as DateTime Objects)

Get the first upcoming date. If not applied, it will return `false`:

```
    {{ entry.timeloop.upcoming ? entry.timeloop.upcoming | date('Y-m-d\\TH:i:sP') : 'no upcoming date' }}
```

Get the date that is  upcoming date after the first. If not applied, it will return `false`:

```
    {{ entry.timeloop.nextUpcoming ? entry.timeloop.nextUpcoming | date('Y-m-d\\TH:i:sP') : 'no next upcoming date' }}
```

### Period Model

Getting the frequency (DateTimePeriod String):

```
    {{ entry.timeloop.period.frequency }}
```

Getting the cycle (Integer):

```
    {{ entry.timeloop.period.cycle }}
```

Displaying the selected days (Array):

```
    {% for day in entry.timeloop.period.days %}
        {{ day }}
    {% endfor %}
```

This will parse the names of the selected days when weekly has been chosen as frequency.

### Timestring Model

Get the ordinal of a monthly set loop (e.g. first, second, ..., last)

**warning:** If the frequency is not set to monthly, the returned value will be `null`.
**warning:** If the frequency is set to monthly and no timestring selection has been made, the returned value will be `none` as `String`.

```
    {{ entry.timeloop.timestring.ordinal ?? 'not set' }}
```

### Reminder Model

Get the reminder date and time before the recurring date occurs

```
    {{ entry.timeloop.reminder | date('Y-m-d\\TH:i:sP') }}
```

### GraphQL

If you want to use the plugin through GraphQL, we've added a GraphQL Type to provide the field data.

You can get the DateTime Types from the data directly for 
* `loopStartDate` will return the start date
* `loopStartTime` will return the start time, defaults to `00:00:00` when no start time has been entered or `showTimes` is set to false.
* `loopEndDate` will return the end date
* `loopEndTime` will return the end time, defaults to `23:59:59` when no end time has been entered or `showTimes` is set to false.
* `loopReminder` will return the reminder for the first upcoming date, if any.

#### Loop Period

You can get the `loopPeriod` object as follows:

```
    loopPeriod {
        frequency
        cycle
        days
        timestring {
          ordinal
          day
        }
    }
```

* `frequency` will return the selected frequency ( P1D / P1W / P1M / P1Y )
* `cycle` will return the entered cycle value
* `days` will return an Array that contains the selected days of the week
* `timestring` will return an object that contains the `ordinal` (e.g. last) and `day` (e.g. saturday)

#### The Dates

To get an array of formatted dates, use `dates`.

##### Dates arguments:

* limit (Integer): add a limit of dates you want to return, default to `100`.
* futureDates (Boolean): if you want to show future dates only, default to `true`.

##### Dates directives:

`formatDateTime(timezone: "Europe/London" format: "d/m/Y")`


```graphql
query{
  entries(section: "section"){
    id,
    ...on section_section_Entry {
      dateCreated,
      title,
      timeloop {
        loopReminder
        loopStartDate
        loopStartTime
        loopEndDate
        loopEndTime
        loopPeriod {
          frequency
          cycle
          days
        }
        getDates(limit: 10, futureDates: false)
        getReminder
        getUpcoming
        getNextUpcoming
      }
    }
  }
}
```

## Timeloop Roadmap

Features for the future:

* Add controllers to be used for a cronjob or other automated tasks
* Make the field translatable
* Provide language translations
* Add the possibility to blocklist dates ( e.g. do not repeat the dates during July/August )
* Add holiday settings ( Bank Holidays to take into consideration when displaying upcoming dates )
* Localise holidays based on the CraftCMS timezone settings

And many more!

Brought to you by [Percipio.London](https://percipio.london)

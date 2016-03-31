---
layout: documentation.twig
title: DateTime
---

# About Windwalker DateTime

`Windwalker\Core\DateTime\DateTime` is sub class of PHP `DateTime`. This class is based on Joomla `JDate` and add some new features.

All PHP DateTime functions are able used to Windwalker DataTime, see [PHP DateTime](http://php.net/manual/en/book.datetime.php)

# Construct

Windwalker DateTime supports Timezone as string.

``` php
use Windwalker\Core\DateTime\DateTime;

$dt = new DateTime('now', 'Asia/Taipei');
```

Auto set default timezone so you won't get PHP default timezone notice.
 
``` php
$dt = new DateTime('now', null);
```

Auto get global timezone, if you set timezone in Windwalker config, DateTime will auto load it.

``` php
$dt = new DateTime('now', true);

// OR

$dt = new DateTime('now', DateTime::TZ_LOCALE);
```

# Convert Timezone

Windwalker DateTime supports easy methods to convert timezone.
  
``` php
$date = '2015-03-31 12:00:00';

$date = DateTime::toServerTime($date, [format], [to tz]);

$date = DateTime::toLocalTime($date, [format], [to tz]);

// Or convert to other timezone

$date = DateTime::convert($date, [from], [to], [format]);
$date = DateTime::convert($date, 'Asia/Taipei', 'Asia/Tokyo', 'Y-m-d H:i:s');
```

# Simple Properties

``` php
$datetime = new DateTime;

$datetime->daysinmonth;
$datetime->dayofweek;
$datetime->dayofyear;
$datetime->isleapyear;
$datetime->day;
$datetime->hour;
$datetime->minute;
$datetime->second;
$datetime->month;
$datetime->ordinal;
$datetime->week;
$datetime->year;
```

# Pre-define Formats

``` php
$datetime = new DateTime;

$datetime->toSql();
$datetime->toISO8601();
$datetime->toRFC822();
$datetime->toUnix();

$date->format(DateTime::FORMAT_YMD);
$date->format(DateTime::FORMAT_YMD_HI);
$date->format(DateTime::FORMAT_YMD_HIS);
```

# Get Local Time

Add `true` to get local time, otherwise you will get UTC time. 

``` php
$datetime = new DateTime('now', 'Asia/Taipei');

$datetime->format('Y/m/d H:i:s', true);
$datetime->toSql(true);
$datetime->toISO8601(true);
$datetime->toRFC822(true);
$datetime->toUnix(DateTime::TZ_LOCALE); // Same as true
```

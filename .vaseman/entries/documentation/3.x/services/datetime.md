---
layout: documentation.twig
title: Chronos (DateTime)
redirect:
    2.1: more/datetime

---

## About Chronos (DateTime)

`Windwalker\Core\DateTime\Chronos` is sub class of PHP `DateTime`. This class is based on Joomla `JDate` and add some new features.

All PHP DateTime functions are able to use on Windwalker `Chronos`, see [PHP DateTime](http://php.net/manual/en/book.datetime.php)

## Construct

Chronos supports Timezone as string.

```php
use Windwalker\Core\DateTime\Chronos;

$dt = new Chronos('now', 'Asia/Taipei');

// Simple create
$dt = Chronos::create();

// Quick get current time as format (Similar as date())
$time = Chronos::current('Y-m-d');
```

Auto set default timezone so you won't get PHP default timezone notice.

```php
$dt = new Chronos('now', null);
```

Auto get global timezone, if you set timezone in Windwalker config, DateTime will auto load it.

```php
$dt = new Chronos('now', true);

// OR

$dt = new Chronos('now', Chronos::TZ_LOCALE);
```

## Convert a date string to another format

```php
Chronos::toFormat($date, 'Y/m/d');
```

## Convert Timezone

Chronos provides easy methods to convert timezone.

```php
$date = '2015-03-31 12:00:00';

$date = Chronos::toServerTime($date, [format], [to tz]);

$date = Chronos::toLocalTime($date, [format], [to tz]);

// Or convert to other timezone

$date = Chronos::convert($date, [from], [to], [format]);
$date = Chronos::convert($date, 'Asia/Taipei', 'Asia/Tokyo', 'Y-m-d H:i:s');
```

## Simple Properties

```php
$datetime = new Chronos();

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

## Pre-define Formats

```php
$datetime = new Chronos();

$datetime->toSql();
$datetime->toISO8601();
$datetime->toRFC822();
$datetime->toUnix();

$date->format(Chronos::FORMAT_YMD);
$date->format(Chronos::FORMAT_YMD_HI);
$date->format(Chronos::FORMAT_YMD_HIS);
```

## Get Local Time

Add `true` to get local time, otherwise you will get UTC time.

```php
$datetime = new Chronos('now', 'Asia/Taipei');

$datetime->format('Y/m/d H:i:s', true);
$datetime->toSql(true);
$datetime->toISO8601(true);
$datetime->toRFC822(true);
$datetime->toUnix(Chronos::TZ_LOCALE); // Same as true
```

## Get Sql Format

```php
$otherLibrary->getDateTime()->format(Chronos::getSqlFormat());

// Use different DB instance
Chronos::getSqlFormat($db);
```

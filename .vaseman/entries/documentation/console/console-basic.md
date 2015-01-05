layout: documentation.twig
title: Console Basic Usage

---

# Use Windwalker Console

Windwalker Console is a powerful CLI tool set to help us do many things. It is based on [Windwalker Console Package](https://github.com/ventoviro/windwalker-console).

To use Windwalker console, type this command in terminal:

``` bash
php bin/console
```

You will see this help information:

``` bash
Windwalker Console - version: 2.0
------------------------------------------------------------

[console Help]

The default application command

Usage:
  console <command> [option]


Options:

  -h | --help       Display this help message.
  -q | --quiet      Do not output any message.
  -v | --verbose    Increase the verbosity of messages.
  --ansi            Set 'off' to suppress ANSI colors on unsupported terminals.

Commands:

  migration    Database migration system.
  seed         The data seeder help you create fake data.
  build        Some useful tools for building system.

Welcome to Windwalker Console.

```

Currently there are only 3 commands available, we'll add more useful tools in the future.

See: [Migration and Seeding](../db/migration.html)



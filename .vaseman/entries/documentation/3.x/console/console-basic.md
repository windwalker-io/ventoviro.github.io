---
layout: documentation.twig
title: Console Basic Usage

---

## Use Windwalker Console

Windwalker Console is a powerful CLI tool set to help us do many things. It is based on [Windwalker Console Package](https://github.com/ventoviro/windwalker-console).

To use Windwalker console, type this command in terminal:

```bash
php windwlaker
```

You will see this help information:

```bash
Windwalker Console - version: 3.x
------------------------------------------------------------

[windwalker Help]

The default application command

Usage:
  windwalker <command> [option]


Options:

  -h | --help              Display this help message.
  -q | --quiet             Do not output any message.
  -v | --verbose           Increase the verbosity of messages.
  --ansi                   Set 'off' to suppress ANSI colors on unsupported terminals.
  -n | --no-interactive    Ignore interactions and assume to default value.

Commands:

  system       System operation.
  run          Run custom scripts.
  asset        Asset management
  migration    Database migration system.
  seed         The data seeder help you create fake data.
  package      Package operations.
  queue        Queue management.

Welcome to Windwalker Console.

```

See: [Migration and Seeding](../db/migration.html)

## No Interactive

Add `-n` or `--no-interactive` that all commands wll ignore questions and use default vlaue.

## Run Custom Scripts

Add a marco to console so we can batch run a set of commands.

Write your scripts in `etc/config.yml`

```yaml
# ...

console:
    script:
        prepare:
            - echo dev > .mode
            - php windwalker asset sync admin
            - php windwalker asset sync front
            - php windwalker asset sync flower
            - php windwalker migration reset
        deploy:
            - git pull
            - composer install
            - php windwalker run prepare
            - php windwalker migration migrate
            - php windwalker asset makesum
            - echo prod > .mode

```

Then you can run your custom script by:

```bash
$ php windwalker run prepare
$ php windwalker run deploy
```

### Auto Answer

If you wish your script can auto answer questions, you can use this format:

```yaml
console:
    script:
        prepare:
            - echo dev > .mode
            - cmd: php windwalker migration drop-all
              in: "y\nn\ny"
            - php windwalker migration reset
```

Separate every answers by `\n` (Must use double quote), so console will help you fill the input.

> You must install `symfony/process`: `~3.0` to support auto-answers.

### Stop Running

By default, all commands no metter success or failure will not break script running.

If you want to stop batch running, just return code `64` in a command:

```php
if (...)
{
    return 64;
}
```

Or throw an exception with code `64`:

```php
throw new \RuntimeException('...', 64);
```

### List Scripts

Use `$ php windwalker run --list` to list all scripts

![p-2016-06-24-001](https://cloud.githubusercontent.com/assets/1639206/16866618/840e9452-4a9f-11e6-865d-47aea77968c8.jpg)

## Register Custom Command

You can add your custom command by editing `etc/app/console.php`:

```php
// ...

'console' => [
    'commands' => [
        'flower' => FlowerCommand::class
    ]
]
```

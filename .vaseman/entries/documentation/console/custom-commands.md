layout: documentation.twig
title: Custom Commands

---

# Introduction of Windwalker Commands

The Nested Command Structure

```
          Console Application (RootCommand)
                         |
              ----------------------
              |                    |
          CommandA              CommandB
              |                    |
        ------------          ------------
        |          |          |          |
    CommandC   CommandD    CommandE   CommandF
```

If we type:

``` bash
php cli/console commandA commandC foo bar -a -bc -d=e --flower=sakura
```

Then we will been direct to `CommandC` class, and the following `foo bar` will be arguments.

``` php
class CommandC extend AbstractCommand
{
    public function execute()
    {
        $arg1 = $this->getArgument(0); // foo
        $arg2 = $this->getArgument(0); // bar
        
        $opt = $this->io->get('d') // e
        $opt = $this->io->get('flower') // sakura
    }
}
```

# Declaring Command Class

This is an example FlowerCommand declaration:

``` php
use Windwalker\Console\Command\Command;

class FlowerCommand extends Command
{
    protected $name  = 'flower';
    protected $usage = 'flower command [--option]';
    protected $help  = 'flower help information';
    protected $description = 'This is first level flower command.';

    public function initialise()
    {
        // We can also set help message in initialise method 
        $this->description('This is first level flower command.')
            ->usage('flower <command> [--option]')
            ->help('flower help information');
    }

    public function doExecute()
    {
        $this->out('This is Flower Command executing.');
    }
}

```

## Register Command in Console:

We can add our Commands in `src/Windwalker/Console/Application.php`:

``` php
// src/Windwalker/Console/Application.php

public function registerCommands()
{
    parent::registerCommands();

    /*
     * Register Commands
     * --------------------------------------------
     * Register your own commands here, make sure you have call the parent, some important
     * system command has registered at parent::registerCommands().
     */

    // Your commands here.
    $this->addCommand(new FlowerCommand);
}
```

Then type `php bin/console -h` will see our new command:

``` bash
Commands:

  migration    Database migration system.
  seed         The data seeder help you create fake data.
  build        Some useful tools for building system.
  flower       This is first level flower command.

```

## Auto Registering

If we create commands in package `Command` folder, every commands will be auto registered to Console:

``` php
// src/Flower/Command/FlowerCommand.php

namespace Flower\Command;

use Windwalker\Console\Command\Command;

// This class will auto register to console because it is located in package's Command folder
class FlowerCommand extends Command
{
    // ...
}
```

# Get Arguments and Options

We can use this code to get arguments and options, setting them in `FooCommand`.

``` php
// src/Flower/Command/FlowerCommand.php

public function initialise()
{
    // Define options first that we can set option aliases.
    $this->addOption(array('y', 'yell')) // First element `y` will be option name, others will be alias
        ->alias('Y') // Add a new alias
        ->defaultValue(0)
        ->description('Yell will make output upper case.');
        
    // Global options will pass to every child.
    $this->addGlobalOption('s')
        ->defaultValue(0)
        ->description('Yell will make output upper case.');
}

public function doExecute()
{
    $name = #this->getArgument(0);

    if (!$name)
    {
        $this->io->in('Please enter a name: ');
    }

    $reply = 'Hello ' . $name;

    if ($this->getOption('y'))
    {
        $reply = strtoupper($reply);
    }

    if ($this->getOption('q'))
    {
        $reply = strtolower($reply);
    }

    $this->out($reply);
}
```

If we type:

``` bash
$ php bin/console flower Asika --yell

# OR

$ php bin/console flower Asika -y
```

The `getOption()` method will auto detect option aliases, then we can get:

```
HELLO: ASIKA
```

> Note: We have to use `$this->addOption()` to define options first, then the `$this->getOption('x')` will be able to 
get the input option which we want. If we didn't do this, we have to use `$this->io->get('x')` 
to get option value, but this way do not support option aliases.

# Add Second Level Commands and more...

FlowerCommand is the first level command in our command tree, if we want to add several commands under FlowerCommand, 
we can use `addCommand()` method. Now we add two `sakura` and `rose` command under `FlowerCommand`.

## Create Command Classes

We declare `SakuraCommand` and `RoseCommand` class first.

``` php
// src/Flower/Command/Flower/SakuraCommand.php

namespace Flower\Command\Flower;

use Windwalker\Console\Command\Command;

class SakuraCommand extends Command
{
    protected $name = 'sakura';
    protected $usage = 'sakura command [--option]';
    protected $help  = 'sakura help';
    protected $description = 'This is second level sakura command.';

    public function initialise()
    {
        $this->addOption(new Option(array('y', 'yell'), 0))
            ->addGlobalOption(new Option('s', 0, 'desc'));
    }

    public function doExecute()
    {
        $this->out('This is Sakura Command executing.');
        
        $arg1 = $this->getArgument(0);
        
        if ($arg1)
        {
            $this->out('Argument1: ' . $arg1);
        }
    }
}
```

Then register them to `FlowerCommand`:

``` php
<?php
// src/Flower/Command/FlowerCommand.php

use Flower\Command\Flower\SakuraCommand;
use Flower\Command\Flower\RoseCommand;

// ...

public function initialise()
{
    $this->addCommand(new SakuraCommand)
        ->addCommand(new RoseCommand);
}
```

OK, let's typing:

``` bash
php bin/console flower sakura
```

We will get:

```
This is Sakura Command executing code.
```

And typing

``` bash
$ cli/console.php flower sakura bloom
```

will get:

```
This is Sakura Command executing code.
Argument1: bloom
```

## Get Child by Path

``` php
$command = $console->getCommand('flower/sakura'); // SakuraCommand

// OR

$command = $command->getChild('foo/bar/baz');
```

# The Prompter

Prompter is a set of dialog tools help us asking questions from user.

``` php
$prompter = new \Windwalker\Console\Prompter\TextPrompter;

$name = $prompter->ask('Tell me your name:', 'default');
```

OR set question in constructor.

``` php
$prompter = new TextPrompter('Tell me your name: ', $this->io);

// If argument not exists, auto ask user.
$name = $this->getArgument(0, $prompter);
```

## Validate Input Value

``` php
$prompter = new \Windwalker\Console\Prompter\ValidatePrompter;

$prompter->setAttempt(3);

$prompter->ask('Please enter username: ');
```

If we didn't type anything, ValidatePrompter will try ask us three times (We set this number by `setAttempt()`).

```
Please enter username:
  Not a valid value.

Please enter username:
  Not a valid value.

Please enter username:
  Not a valid value.
```

We can set closure to validate our rule:

``` php
$prompter->setAttempt(3)
    ->setNoValidMessage('No valid number.')
    ->setHandler(
    function($value)
    {
        return $value == 9;
    }
);

$prompter->ask('Please enter right number: ');
```

Result

```
Please enter right number: 1
No valid number.

Please enter right number: 2
No valid number.

Please enter right number: 3
No valid number.
```

If validate fail, we can choose shut down our process:
 
``` php
// ...

$prompter->failToClose(true, 'Number validate fail and close');

$prompter->ask('Please enter right number: ');
```

Result

```
Please enter right number:
No valid number.

Please enter right number:
No valid number.

Please enter right number:
No valid number.

Number validate fail and close
```

## Select List

``` php
$options = array(
    's' => 'sakura',
    'r' => 'Rose',
    'o' => 'Olive'
);

$prompter = new \Windwalker\Console\Prompter\SelectPrompter('Which do you want: ', $options);

$result = $prompter->ask();

$command->out('You choose: ' . $result);
```

Output

```
  [s] - sakura
  [r] - Rose
  [o] - Olive

Which do you want: r
You choose: r
```

## Boolean Prompter

BooleanPrompter convert input string to boolean type, the (y, yes, 1) weill be `true`, (n, no, 0, null) will be `false`.

``` php
$prompter = new \Windwalker\Console\Prompter\BooleanPrompter;

$result = $prompter->ask('Do you wan to do this [Y/n]: ');

var_dump($result);
```

Result

```
Do you wan to do this [Y/n]: y
bool(true)
```

## Available Prompters

- TextPrompter
- SelectPrompter
- CallbackPrompter
- ValidatePrompter
- NotNullPrompter
- PasswordPrompter

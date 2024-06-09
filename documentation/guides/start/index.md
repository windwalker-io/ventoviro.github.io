---
layout: doc
---

# Installation

## Requirement

- PHP 8.2 or above
- Node 16up / npm or yarn
- Composer and web server

## Starter Package

Go to https://github.com/ventoviro/windwalker-starter

Follow the instructions (replace {folder} with your installation directory)

``` 
composer create-project windwalker/starter {folder} ^4.0 --remove-vcs
```

After installing a series of dependencies, it will ask a few questions. Enter the database
information as instructed. Then the installer will continue to install the CSS/JS
dependencies.

This tutorial will use `pdo_mysql` as default.

```shell
> Windwalker\Core\Composer\StarterInstaller::rootInstall
App Name: WW Tutorial
Remove .lock files from .gitignore.

Enter a custom secret [Leave empty to auto generate]:

Do you want to use database? [Y/n]: y
Please select database drivers:
  [1] pdo_mysql
  [2] mysqli
  [3] pdo_pgsql
  [4] pgsql
  [5] pdo_sqlsrv
  [6] sqlsrv
  [7] pdo_sqlite
> 1
Selected driver: pdo_mysql
Database host [localhost]:
Database name [acme]: ww4tut
Database user [root]:
Database password:


Database config setting complete.

Install complete.
> php windwalker run prepare

Start Custom Script: prepare
---------------------------------------
>>> yarn install
```

After the installation is complete, open the localhost URL:

```
http://localhost/{site}/www
```

or start the php server:

```shell
php windwalker server:start
```

Then open `http://localhost:8000` to see the homepage.

![home](https://github.com/lyrasoft/ww4tut/assets/1639206/2134e4b7-6caa-41b0-ac2b-cc69f3944b0e)

Go to: `http://localhost:8000/admin` to see a simple fake dashboard.

![dashboard](https://github.com/lyrasoft/ww4tut/assets/1639206/9ad6fa6a-8466-4332-b34e-aeab916e45e7)


### Install Default Database Files

If you want to use database, please install `windwalker/database` and `windwalker/orm` first:

```shell
composer require windwalker/database windwalker/orm
```

Then run migration (add `-s` to run seeder)

```shell
php windwalker mig:go -s
```

It will ask if you really want to run migration/seeding.

```shell
Do you really want to run migration/seeding?: [N/y] y
```

If you don't want to be asked this question again and want to run it directly, you can add `-f` to
future migration commands.

After execution:

```shell
$ php windwalker mig:go -s
Do you really want to run migration/seeding?: [N/y] y

Backing up SQL...
SQL backup to: ...


Migration start...
==================

2021061915530001 AcmeInit UP... Success

Completed.


Seeding...
==========

Import seeder: Acme Seeder (/acme-seeder.php)
  (15) \
  Import completed...
```

Open the project URL `http://{site}/www/admin/acme`, and if you see the following list, it means
success.

![acme](https://github.com/lyrasoft/ww4tut/assets/1639206/60bd49ec-454d-44b3-ae35-6554d318807e)

## Directory Structures

| **Directory** | **Purpose**                                                                  |
|---------------|------------------------------------------------------------------------------|
| cache         | Cache directory                                                              |
| etc           | Configuration files                                                          |
| logs          | Log files                                                                    |
| resources     | Various resource files- assets / languages / migrations / registry / seeders |
| routes        | Route configuration files                                                    |
| src           | Project code files                                                           |
| tmp           | Temporary files                                                              |
| vendor        | External libraries                                                           |
| views         | Template files                                                               |
| www           | Public directory                                                             |

## CLI Auto-Completion

Windwlaker Console currently supports auto-completion on macOS `zsh` terminal. If you want to enable it, run this command once:

```shell
php windwalker completion

source ~/.zshrc
```

And now Windwalker auto-completion have registered to your zsh profile and can work for every Windwalker project. You can auto complete any commands or arguments when you're using console interface.


![auto-completion](https://github.com/windwalker-io/framework/assets/1639206/1a946e9e-8b54-4a0f-bde1-4b828b310cd8)


---
layout: documentation.twig
title: Authentication
redirect:
    2.1: more/authentication

---

## Introduction

Windwalker user system is extendable, before we start use user auth, we must do some prepare to initial user system.

## Prepare User System

## Create UserHandler

`UserHandlerInterface` help us handler user CRUD and login/logout actions, create a Model implements `UserHandlerInterface`
in your package.

``` php
namespace Flower\Model;

use Windwalker\Core\Model\DatabaseModelRepository;
use Windwalker\Core\User\UserData;
use Windwalker\Core\User\UserDataInterface;
use Windwalker\Core\User\UserHandlerInterface;
use Windwalker\Ioc;

class UserModel extends DatabaseModelRepository implements UserHandlerInterface
{
	protected $table = 'users';

	public function load($conditions)
	{
		$data = $this->getDataMapper()->findOne($conditions);

		return new UserData($data->dump());
	}

	public function save(UserDataInterface $user)
	{
		$result = $this->getDataMapper()->saveOne($user->dump());

		$user->id = $result['id'];

		return $user;
	}

	public function delete($conditions)
	{
		return $this->getDataMapper()->delete($conditions);
	}

	public function login(UserDataInterface $user)
	{
	    unset($user->password);

		Ioc::getSession()->set('user', $user->dump());

		return true;
	}

	public function logout(UserDataInterface $user = null)
	{
		Ioc::getSession()->remove('user');

		return true;
	}
}
```

### Migration and Seeding

Use this simple schema to create user table, see [Migration](../db/migration.html):

``` php
$this->createTable('users', function (Schema $sc)
{
    $sc->primary('id')->comment('Primary Key');
    $sc->varchar('name')->comment('Full Name');
    $sc->varchar('username')->comment('Login name');
    $sc->varchar('email')->comment('Email');
    $sc->varchar('password')->comment('Password');

    $sc->addIndex('id');
    $sc->addIndex('username');
    $sc->addIndex('email');
});
```

And you can add seeder to generate fake user data:

``` php
$faker = \Faker\Factory::create();

// Please do not use `pass123456` in production site
$pass = (new Password)->create('pass123456');

$model = UserModel::getInstance();

foreach (range(1, 50) as $i)
{
    $data = [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->email,
        'password' => $pass
    ];

    $model->save(new UserData($data));

    $this->outCounting();
}
```

Add this handler to `etc/app/web.php`:

``` php
// ...


	'user' => [
		'handler' => \Flower\Model\UserModel::class,
		'methods' => [
		],
		'policies' => [

		]
	]
```

## Load and Save Users

Now we can do CRUD for user table:

``` php
use Windwalker\Core\User\User;

$user = User::get(1); // get User ID=1
$user = User::get(['username' => 'christina']); // get Username=christina

$user->name = 'Christina';

User::delete(1); // Delete User ID=1
```

### Get User

``` php
use Windwalker\Core\User\User;

// Get a user by id
$user = User::get(12);

// Get user by conditions
$user = User::get(array('username' => 'christina'));
```

### Save User

``` php
use Windwalker\Crypt\Password;

$user->username = 'riaok3784';
$user->password = 'abc1234';

// Hash password
$user->password = (new Password)->create($user->password);

User::save($user);
```

### Delete User

``` php
// Delete by id
User::delete(12);

// Delete by conditions
User::delete(array('username' => 'richael3784'));
```

## Create Authentication Method

Currently our `UserHandler` class can only load/save user from database, but we need it to support login/logout from databse or other sources.

Windwalker Authentication allow developers attach multiple methods to match user(See [Authentication Package](https://github.com/ventoviro/windwalker-authentication)).

This image described how authentication methods working.

![p-2015-01-02-5](https://cloud.githubusercontent.com/assets/1639206/5595002/07d3235a-92a2-11e4-8f1f-5622e2af7254.jpg)

### DatabaseMethod

We create a method class to find user from database.

``` php
namespace Flower\User;

use Flower\Model\UserModel;
use Windwalker\Authentication\Authentication;
use Windwalker\Authentication\Credential;
use Windwalker\Authentication\Method\AbstractMethod;
use Windwalker\Crypt\Password;

class DatabaseMethod extends AbstractMethod
{
	public function authenticate(Credential $credential)
	{
		// Do not allow empty username or password
		if (!$credential->username || !$credential->password)
		{
			$this->status = Authentication::EMPTY_CREDENTIAL;

			return false;
		}

		// Do not allow email as username
		if (strpos($credential->username, '@') !== false)
		{
			$this->status = Authentication::INVALID_USERNAME;

			return false;
		}

		// Get User from database
		// You can also use User::get(['username' => $credential->username])
		$user = UserModel::getInstance()->load(['username' => $credential->username]);

		if ($user->isNull())
		{
			$this->status = Authentication::USER_NOT_FOUND;

			return false;
		}

		// User found, then we check password
		if (!(new Password)->verify($credential->password, $user->password))
		{
			$this->status = Authentication::INVALID_PASSWORD;

			return false;
		}

		// Confirm user exists, bind user data into Credential since it is referenced
		$credential->bind($user);

		// Return TRUE and success status
		$this->status = Authentication::SUCCESS;

		return true;
	}
}
```

And register to `etc/app/web.php`:

``` php

// ...

	'user' => [
		'handler' => ...,
		'methods' => [
			\Flower\User\DatabaseMethod::class
		],
		'policies' => [

		]
	]
```

OK now we can login User by credential.

## Login and Logout

### Login User

``` php
// Prepare user data
$credential = new Credential;
$credential->username = 'richael2123';
$credential->password = '12345678';

try
{
    $bool = User::login($credential);
}
catch (AuthenticateFailException $e)
{
    $messages = $e->getMessages();
}
```

Remember me:

``` php
$bool = User::login($credential, true);
```

Our UserHandler set user login data to session `user` key, see `$_SESSION['user']`:

``` php
show($_SESSION);

Array
(
    [_default] => Array
        (
            ...
            [user] => Array
                (
                    [username] => richael2123
                    [id] => 2
                    [name] => Richael
                    [email] => richael@block.net
                    [_authenticated_method] => 0
                )
        )
    [_flash] => Array
        (
        )
)
```


### Logout User

``` php
$bool = User::logout();
```

### Force Login User

Use `makeUserLoggedIn()` to force login a user data to session without authentication.

``` php
$user = [
    'username' => 'richael2123',
    'name' => 'Richael'
];

User::makeUserLoggedIn($user);
```

### Get Current User

``` php
$user = User::get();

// Check user is logged-in
$user->isMember();

// Check user is not logged-in
$user->isGuest();
```

## Authenticate From Remote

This is an example to auth user from remote server.

``` php
use Windwalker\Http\HttpClient;

// ...

class RemoteMethod extends AbstractMethod
{
    public function authenticate(Credential $credential)
    {
        // Use HttpClient to get remote json data
        $response = (new HttpClient)->get('http://myapiserver.com/user/auth', [
            'username' => $credential->username,
            'password' => $credential->password
        ]);

        if ($response->getStatusCode() != 200)
        {
            $this->status = Authentication::USER_NOT_FOUND;

            return false;
        }

        $user = json_decode($response->getBody()->__toString());

        // Confirm user exists, bind user data into Credential since it is referenced
        $credential->bind($user);

        // Return TRUE and success status
        $this->status = Authentication::SUCCESS;

        return true;
    }
}
```

Then add this class to `etc/app/web.php` so Windwalker will check two methods to find user.

``` php
// ...

    'user' => [
		'handler' => ...
		'methods' => [
			\Flower\User\DatabaseMethod::class,
			\Flower\User\RemoteMethod::class
		],
		'policies' => [

		]
	]
```

### Dependency Injection

If your method class dependent on other classes, just add it on constructor, The Ioc container will auto inject it:

``` php
use Windwalker\Core\Application\WebApplication;

class DatabaseMethod extends AbstractMethod
{
    protected $app;

    public function __contruct(WebApplication $app)
    {
        $this->app = $app;
    }

    // ...
```

## Check User Is Allowed To Login

You can check a user has access to login or not, use listener to listen `onUserAuthorisation` event:

``` php
namespace Flower\Listener;

use Windwalker\Core\User\Exception\AuthenticateFailException;
use Windwalker\Core\User\UserDataInterface;
use Windwalker\Event\Event;

class UserAuthListener
{
	public function onUserAuthorisation(Event $event)
	{
		/** @var UserDataInterface $user */
		$user = $event['user'];

		if (!$user->activated)
		{
		    // Return false to result
			$event['result'] = false;

			// OR throw exception
			throw new AuthenticateFailException('User not activated');
		}

		if ($user->is_blocked)
		{
			throw new AuthenticateFailException('User is disbaled');
		}
	}
}
```

Then register it to `etc/app/web.php`:

``` php
// ...

    'listeners' => [
		400 => \Flower\Listener\UserAuthListener::class
	],
```

Now Windwalker will auto block users with no access to login.

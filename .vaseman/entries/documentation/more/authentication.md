layout: documentation.twig
title: Authentication

---

# Introduction

Building user system in Windwalker is very easy, Windwalker includes a simple Authenticate package to handle user auth.

# Default User Package

In Windwalker Starter, there is an User package in `src/Windwalker/User` and has been registered to system. 
You can directly modify it to what you needed.

Before using it, you may run this command to import default user package schema:

``` bash
php bin/console migration migrate -p=wwuser
```

## Get User

``` php
use Windwalker\Core\Authenticate\User;

// Get current logged-in user
$user = User::get();

// Get a user by id
$user = User::get(12);

// Get user by conditions
$user = User::get(array('username' => 'christina'));
```

## Login User

``` php
// Prepare user data
$credential = new Credential;
$credential->username = 'richael2123';
$credential->password = '12345678';

$bool = User::login($credential);
```

Remember me:

``` php
$bool = User::login($credential, true);
```

## Logout User

``` php
$bool = User::logout();
```

## Save User

``` php
use Windwalker\Crypt\Password;

$user->username = 'riaok3784';
$user->password = 'abc1234';

// Hash password
$password = new Password;

$user->password = $password->create($user->password);

User::save($user);
```

## Delete User

``` php
// Delete by id
User::delete(12);

// Delete by conditions
User::delete(array('username' => 'richael3784'));
```

# Create Your Own User Handler

Windwalker Built-in Authenticate system can register a custom user handler, this user handler should implement the `UserHandlerInterface`:

``` php
use Windwalker\Core\Authenticate\UserDataInterface;
use Windwalker\Core\Authenticate\UserHandlerInterface;

class MyUserHandler implements UserHandlerInterface
{
	/**
	 * load
	 *
	 * @param array $conditions
	 *
	 * @return  mixed|false
	 */
	public function load($conditions) {}

	/**
	 * save
	 *
	 * @param UserDataInterface $user
	 *
	 * @return  UserDataInterface
	 */
	public function save(UserDataInterface $user) {}

	/**
	 * delete
	 *
	 * @param UserDataInterface $user
	 *
	 * @return  boolean
	 */
	public function delete(UserDataInterface $user) {}

	/**
	 * login
	 *
	 * @param UserDataInterface $user
	 *
	 * @return  boolean
	 */
	public function login(UserDataInterface $user) {}

	/**
	 * logout
	 *
	 * @param UserDataInterface $user
	 *
	 * @return bool
	 */
	public function logout(UserDataInterface $user) {}
}
```

See: [Default UserHandler](http://goo.gl/P0b2qk) to learn how to write your use handler.

Then you may register it before application execute:

``` php
use Windwalker\Core\Authenticate\User;

User::setHandler(new MyUserHandler);
```

# Create Authenticate Method

Windwalker Authenticate allow developers attach multiple methods to match user(See [Document](https://github.com/ventoviro/windwalker-authenticate)).

This image described how authenticate methods working. 

![p-2015-01-02-5](https://cloud.githubusercontent.com/assets/1639206/5595002/07d3235a-92a2-11e4-8f1f-5622e2af7254.jpg)

## Example Method to Find User From Database

``` php
use Windwalker\Authenticate\Authenticate;
use Windwalker\Authenticate\Credential;
use Windwalker\Authenticate\Method\AbstractMethod;
use Windwalker\Crypt\Password;
use Windwalker\DataMapper\DataMapper;

class DatabaseMethod extends AbstractMethod
{
	public function authenticate(Credential $credential)
	{
		// Not allow empty password
		if (!$credential->username || !$credential->password)
		{
			$this->status = Authenticate::EMPTY_CREDENTIAL;

			return false;
		}

		$datamapper = new DataMapper('users');

		// Use user data to find user
		$user = $datamapper->findOne(array('username' => $credential->username));

		// User not found
		if ($user->isNull())
		{
			$this->status = Authenticate::USER_NOT_FOUND;

			return false;
		}

		// User found, start to verify password
		$password = new Password;

		if (!$password->verify($credential->password, $user->password))
		{
			$this->status = Authenticate::INVALID_CREDENTIAL;

			return false;
		}

		// Password verify success, return user data.
		$credential->bind($user);

		$this->status = Authenticate::SUCCESS;

		return true;
	}
}
```

Then we must register this method before Application executed:

``` php
$auth = \Windwalker\Ioc::getAuthenticate();
$auth->addMethod('database', new DatabaseMethod);

// OR

User::addMethod('database', new DatabaseMethod);
```

layout: documentation.twig
title: Session

---

# Configuration

Session configuration is in `/etc/config.yml`.
 
``` yaml
session:
    handler: native
    expire_time: 15
```

By default, Windwalker use php native to handler session data. The expire time unit is minute.

# Get Session Object

``` php
$session = \Windwalker\Ioc::getSession();

// OR

$session = $container->get('system.session');
```

# Store And Get Session Data

``` php
$session->set('flower', 'sakura');

$data = $session->get('flower', 'default');

$session->exists('animal'); // bool
```

# Restart Session

``` php
$session->restart();
```

# Session Bags

Session bag is a data storage to store data, we can add many bags to Session object and access them.

## Use Default Bag

Get Default Bag

``` php
$session->getBag('default');
```

Get data from default bag.

``` php
$session->get('foo');

// OR
$session->getBag('default')->get('foo');
```

## Use Custom Bags

``` php
use Windwalker\Session\Bag\SessionBag;

$session->setBag('mybag', new SessionBag);

// Get data
$myBag = $session->getBag('mybag');

$myBag->set('foo', 'bar');
$myBag->get('foo', 'default');
```

We can use Namespace to get data from bags

``` php
// Get form default bag
$session->get('foo', 'default', 'mybag');

// Get from mybag
$session->get('foo', 'default', 'mybag');

// Set to mybag
$session->set('foo', 'bar', 'mybag');
```

# Flash Data

Flash bag is a data temporary storage, if we take data out, the bag will be clear.

``` php
$session->addFlash('Save success.', 'info');
$session->addFlash('Login Fail.', 'error');

// Take all messages and clear
$allMessages = $session->getFlashes();

// Peek messages but don't clear
$session->getFlashBag()->all();
``` 

# Handlers

Windwalker Session provides many handlers to storage session, edit the config file.

``` yaml
session:
    handler: memcached
    expire_time: 15
```

## Use Database Handler

Before using database session, we have to add table schema information to session config.

``` yaml
session:
    handler: database
    expire_time: 15
    table: sessions
    id_col: id
    data_col: data
    time_col: time
```

> NOTE: Database Handler will be implemented in next release.

## Available Handlers

- apc
- database
- memcached
- native
- xcache

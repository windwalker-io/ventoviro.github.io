---
layout: documentation.twig
title: Authorisation
redirect:
    2.1: more/authentication

---

Authorisation service is s simple ACL manager to help us manage user access.

## Add A Policy To Check Access

``` php
use Windwalker\Core\User\User;
use Windwalker\Core\User\UserDataInterface;

User::addPolicy('can.edit.article', function (UserDataInterface $user, Data $article)
{
    return $user->isAdmin() || $user->id == $article->author_id;
});

// Check access
$user = User::get();
$article = $model->getAtricle();

User::authorise('can.edit.article', $user, $article); // boolean
```

## Use Class Policy

``` php
use Windwalker\Core\Application\WebApplication;

class CanEditArticlePolicy implements \Windwalker\Authorisation\PolicyInterface
{
    public function authorise($user, $article = null)
    {
        return User::authorise('super.user', $user) || $user->id == $article->author_id;
    }
}

// Add to UserManager
User::addPolicy('can.edit.article', new CanEditArticlePolicy);
```

Or register in config:

``` php
// etc/app/web.php

// ...

    'user' => [
		// ...
		'policies' => [
			'can.edit.article' => \Flower\Policy\CanEditArticlePolicy::class
		]
	]
```

If your policy dependent on other classes, just add it on constructor, The Ioc container will auto inject it:

``` php
use Windwalker\Core\Application\WebApplication;

class CanEditArticlePolicy implements \Windwalker\Authorisation\PolicyInterface
{
    protected $app;

    public function __contruct(WebApplication $app)
    {
        $this->app = $app;
    }

    // ...
```

## Use PolicyProvider to Register Multiple Policies

``` php
use Windwalker\Authorisation\AuthorisationInterface;
use Windwalker\Authorisation\PolicyProviderInterface;

class ArticlePolicyProvider implements PolicyProviderInterface
{
    public function register(AuthorisationInterface $auth)
    {
        $auth->addPolicy('can.create.article', function () { ... });
        $auth->addPolicy('can.edit.article', function () { ... });
        $auth->addPolicy('can.edit.own.article', function () { ... });
        $auth->addPolicy('can.delete.article', function () { ... });
    }
}

// Register policies
User::registerPolicyProvider(new ArticlePolicyProvider);
```

Also, you can register provider in `etc/app/web.php`:

``` php
// ...

    'user' => [
		// ...
		'policies' => [
			'article' => \Flower\Policy\ArticlePolicyProvider::class
		]
	]
```

If your provider dependent on other classes, just add it on constructor, The Ioc container will auto inject it:

``` php
use Windwalker\Core\Application\WebApplication;
use Windwalker\Authorisation\PolicyProviderInterface;

class ArticlePolicyProvider implements PolicyProviderInterface
{
    protected $app;

    public function __contruct(WebApplication $app)
    {
        $this->app = $app;
    }

    // ...
```

## Authorise in Blade & Edge Template

Use `@auth` or `@can` directive:

``` php
@can('article.edit', $user)
    <a href="#">Edit</a>
@else
    ---
@endcan
```

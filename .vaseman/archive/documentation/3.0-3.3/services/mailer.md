---
layout: documentation.twig
title: Mailer

---

## Use Mailer

Windwalker provides an interface to integrate different mail packages, currently we support [Swift Mailer](http://swiftmailer.org/)
as default, you must install `"swiftmailer/swiftmailer": "~5.0"` first.

Then add your mail server information to `etc/secret.yml`:

``` yaml
# ...

mail:
    from:
        name: Sender Name
        email: sender@mail.com
    smtp:
        transport: smtp
        security: tls
        host: your.smtp.net
        port: 2525
        username: *****
        password: *****

        # Verify SSL
        verify: false

        # Local Domain
        local: your.domain.com
    
    # Sendmail command if use this
    sendmail: /usr/sbin/sendmail
```

Currently available transports:

- smtp (SMTP server)
- mail (PHP default `mail()` function)
- sendmail (Use system sendmail)

## Create Message

``` php
use Windwalker\Core\Mailer\Mailer;

/** @var  $message \Windwalker\Core\Mailer\MailMessage */
$message = Mailer::createMessage('Subject');

$message->to('foo@foo.com')
    ->from('my@mail.com') // If you don't set from, Mailer will use the `from` info in config.yml
    ->cc(...)
    ->bcc(...)
    ->html(true) // Default is true
    ->body('Mail body');

// Send it
Mailer::send($message);
```

### Add Mail By Array

You can use array as a set of mail to `to()`, `from()`, `cc()` and `bcc()`.

``` php
$message->to(['foo@mail.com', 'bar@mail.com']);

// OR

$message->to([
    'foo@mail.com' => 'Name Foo',
    'bar@mail.com' => 'Name Bar',
]);
```

### Render Body From View

Use view to render mail in controller:

``` php
$view = $this->getView('Mail');

$view['user'] = User::get();
$view['link'] = $link;
$view['content'] = $content;

$message->body($view->setLayout('mail.notify')->render());
```

Or use message built-in render method:

``` php
// ...

$message->renderBody('mail.notify', $data, 'edge');

Mailer::send($message);
```

### Attachment

Use file path.

``` php
$message->attach('/path/to/file.pdf');
$message->attach('/path/to/file2.pdf', , 'downloaded2.pdf', 'application/pdf');

Mailer::send($message);
```
Use `MailAttachment` class:

``` php
use Windwalker\Core\Mailer\MailAttachment;

$message->attach(new MailAttachment('/path/to/fil.pdf'));

// Or set custom body
$attachment = new MailAttachment;
$attachment->setBody('<html>TEST</html>');

$message->attach($attachment, 'test.html');
```

## Pre-Defined Messages

You can declare some message classes to quickly re-use them.

``` php
namespace Flower\Mail;

// ...

class CheckoutMessage extends MailMessage
{
    public static function create(User $user = null, Data $product = null)
    {
        // Prepare default data for test
        $user = $user ? : User::get();
        $product = $product ? : new Data;

        return (new static('You checkout a product'))
            ->to($user->email, $user->name)
            ->bcc('admin@my-dsite.com')
            ->renderBody('checkout', [
                'user' => $user,
                'product' => $product
            ]);
    }
}
```

Now just create this instance to send mail:

``` php
use Flower\Mail\CheckoutMessage;

Mailer::send(CheckoutMessage::create($user, $product));
```

## Style Inliner

Windwalker Includes a simple CSS inliner to help us compile CSS to inline styles that makes our email show normally with
some email clients which does not support outside CSS.

To enable CSS inliner, you must install `"tijsverkoyen/css-to-inline-styles": "~2.0"` first, and add this listener to `etc/app/windwalker.php`:

``` php
// etc/app/windwalker.php or web.php

    'listeners' => [
        'inliner' => \Windwalker\Core\Mailer\Listener\MailInlinerListener::class
    ]
```

Now write your mail template with some styles and send mail:

``` php
@extends('_global.mail-wrapper')

<!-- Your base mail styles -->
@php( $asset->addCSS('css/mail-style.css') )

<style>
    /* Some inline styles */

    h1, h2, h3 {
        color: #444444;
    }

    p {
        line-height: 1.5
    }

    .btn {
        padding: 5px 7px;
    }
</style>

<!-- Mail body -->

<h1>Hello</h1>
<p>
    World
</p>
<a class="btn" href="#">Readmore</a>
```

This mail template will be compiled to:

``` html
<div id="my-mail-wrapper" style="...">
    <h1 style="color: #444444;">Hello</h1>
    <p style="line-height: 1.5;">
        World
    </p>
    <a style="padding: 5px 7px;" class="btn" href="#">Readmore</a>
</div>
```

> You must use `$asset` to include outside CSS and write in-page CSS in `<style>` tag.
> Do not use `<link>` tag to include CSS.

> ------
> Due to the performance reason, please don't include whole CSS framework like bootstrap or foundation,
> Try to write your own mail style to make sure the compiler fast enough.

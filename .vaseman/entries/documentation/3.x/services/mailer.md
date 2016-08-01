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

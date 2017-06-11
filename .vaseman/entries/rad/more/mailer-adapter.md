---

layout: rad.twig
title: Mailer Adapters

---

## Maingun Adapter

To use [Mailgun](https://www.mailgun.com/) adapter instead Swiftmailer, make sure you installed `mailgun/mailgun-php`, 
and add MailgunProvider to config:
 
```php
	'providers' =>[
		// ...
		//'mailer_adapter' => \Windwalker\Core\Mailer\SwiftMailerProvider::class,
		'mailer_adapter' => \Phoenix\Provider\MailgunProvider::class
	],
```

And add Mailgun API key to your `secret.yml`

```yaml
# ...

mail:
    # ...
    sendmail: /usr/sbin/sendmail
    
    # Add here
    mailgun:
        key: ...
```

Now you are able to send mail by Mailgun's API without SMTP protocol.

## SendGrid Adapter

To use [SendGrid](https://sendgrid.com/) API, please install `sendgrid/sendgrid 5.*` first, then add provider:

```php
	'providers' =>[
		// ...
		'mailer_adapter' => \Phoenix\Provider\SendgridProvider::class
	],
```

Also add sendgrid API key options to `secret.yml`:

```yaml
# ...

mail:
    # ...
    sendmail: /usr/sbin/sendmail
    
    # Add here
    sendgrid:
        key: ...
```

And your application can send mail through SendGrid API. 

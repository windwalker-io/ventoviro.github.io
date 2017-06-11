---
layout: documentation.twig
title: Queue

---

## About Queue Service

Windwalker Queue provides an universal interface to wrap different message queue services, and a simple `Job` interface 
 to easily manage your tasks. Currently we support these drivers:
 
- [SQS](https://aws.amazon.com/sqs) (Amazon Simple Queue Service)
- [IronMQ](https://www.iron.io/)
- [RabbitMQ](https://www.rabbitmq.com/) (AMQP)
- [Beanstalkd](http://kr.github.io/beanstalkd/)
- Database
- Sync (No queue, execute immediately)

> This service is inspired by Laravel and we try to implement it in our way.

## Configuration

In `etc/secret.yml`, you can see queue config:

```yaml
queue:
    connection: sync
    sync:
        driver: sync
    database:
        driver: database
        table: queue_jobs
        queue: default
        timeout: 60
    sqs:
        driver: sqs
        key:
        secret:
        queue: default
        region: ap-northeast-1
    ironmq:
        driver: ironmq
        project_id:
        token:
        queue: default
        region: mq-aws-eu-west-1-1
    rabbitmq:
        driver: rabbitmq
        queue: default
    beanstalkd:
        driver: beanstalkd
        queue: default
        host: 127.0.0.1
        timeout: 60
    failer:
        driver: database
        table: queue_failed_jobs
```

Change the `connection` option and Windwalker will use different drivers.

### Sync

`sync` is default driver, it will not connect to any message queue service but only execute jobs immediately.
  
### Database

`database` uses your database as queue storage, you must create migration file first by this command:
 
```bash
php windwalker queue table
```

### SQS

`sqs` connect to AWS SQS service, you must prepare your access key and secret, fill in `key` and `secret` options.

> Need `aws/aws-sdk-php` package installed.

### IronMQ

`ironmq` uses iron.io's MQ service, please create an account and a `project` first, then get `project_id` and `token` from project setting.
 
> Need `iron-io/iron_mq` package installed.
 
### RabbitMQ

`rabbitmq` must install and run RabbitMQ first. By default, you don't need any host information, Windwalker will connect to `localhost:5672`
RabbitMQ instance with `guest/guest` credential.

If you want to configure the connection and account, add these options after rabbitmq setting:

```yaml
rabbitmq:
    driver: rabbitmq
    queue: default
    # Add these
    host: localhost
    port: 5672
    user: guest
    password: guest
```

> Need `php-amqplib/php-amqplib` package installed.

### Beanstalkd

`beanstalkd` is a very simple, lightweight message queue, just install it and run, then connect to `127.0.0.1` to use it.
You can change the host by modify settings.

> Need `pda/pheanstalk` package installed.

## Get Queue Object

```php
// In controller
$queue = $this->app->queue;

// By Container
$queue = $container->get('queue');

// By Ioc
$queue = Ioc::getQueue();
```

## Create A Job

You task or logic must wrap in a `JobInterface` instance:

```php
class HelloJob implements JobInterface
{
	public function getName()
	{
		return 'hello';
	}

	public function execute()
	{
		echo 'Hello';
	}
}
```

Then push it to queue:

```php
// In controller

$this->app->queue->push(new HelloJob);
```

Now we can wait works handle it later.
 
### Add More Information to Job

Sometimes you need more information to handle things, add them to constructor:
 
```php
class HelloJob implements JobInterface
{
	protected $url;
	protected $path;
	protected $size;
	protected $crop;

	public function __construct($url, $path, $size = 600, $crop = true)
	{
		$this->url = $url;
		$this->path = $path;
		$this->size = $size;
		$this->crop = $crop;
	}

	public function getName()
	{
		return 'hello';
	}

	public function execute()
	{
	    $imgData = (new HttpClient)->get($this->url);
	    
		ImageHelper::load($imgData)
            ->resize($this->size, $this->size, $this->crop)
            ->save($this->path)
	}
}
```

Then inject these information when you creating Jobs:

```php
$this->app->queue->push(new HelloJob(
    'http://example/image.jpg',
    WINDWALKER_PUBLIC . '/images/image.jpg',
    400,
    true
));
```

### Create Job With System Services

If you need some system object, use DI pattern to declare it in constructor:

```php
use Windwalker\Core\Error\Handler\ErrorHandlerInterface;
use Windwalker\Core\Logger\LoggerManager;

class HelloJob implements JobInterface
{
	protected $error;
    protected $data;
    private $logger;

    public function __construct(ErrorHandlerInterface $error, LoggerManager $logger, $data = [])
    {
        $this->error = $error;
        $this->data = $data;
        $this->logger = $logger;
    }

	public function getName()
	{
		return 'hello';
	}

	public function execute()
	{
		if (/* Failure */)
		{
		    $this->logger->error('error', 'Something wrong...');
		}
	}
}
```

And you must resolve the dependencies by container:

```php
$this->app->queue->push(
    $this->container->newInstance(HelloJob::class, ['data' => ['foo', 'bar']])
);
```

## Use Queue Object

### Push

Simple use push to add a job object as new message:

```php
$queue->push(new MyJob($data));
```

Push message but wait for 10 seconds later to run:

```php
$queue->push(new MyJob($data), 10);
```

Push to directly queue:

```php
$queue->push(new MyJob($data), 0, 'flower');
```

Push raw data instead job object:

```php
$queue->pushRaw(['flower' => 'sakura'], 0, 'flower');
```

### Pop, Delete and Release

Use `pop()` to get next message:

```php
$message = $queue->pop(); // QueueMessage object

$message->getJob();
$message->getBody();
$message->getRawBody();
$message->getId();
$message->getAttempts();
$message->get('flower'); // Get data from body
```

Delete a message:

```php
$queue->delete($message);

// argument should be a QueueMessage object
$message = new \Windwalker\Core\Queue\QueueMessage;
$message->setId($id);

$queue->delete($message);

// You can delete by ID
$queue->delete($id);

// Check this message deleted
$message->isDeleted();
```

Release back to queue list (attempts will auto +1):

```php
$queue->release($message);

// You can release by ID
$queue->release($id);

// Wait a while to run again:
$queue->release($message, 15);
```

## Run Jobs By Worker

Windwalker has a worker to help you create daemon to run jobs, use this command:

```bash
php windwalker queue worker
```

Run once and set max fail attempts to 3 times:

```bash
php windwalker queue worker --once --tries=3
```

Every loop will sleep 1 second, you can set a shorter period, and set max time limit per job:

```bash
php windwalker queue worker --sleep=0.3 --timeout=120
```

The options:

```bash
  -c | --connection        The connection of queue.
  -o | --once              Only run next job.
  -d | --delay             Delay time for failed job to wait next run.
  -f | --force             Force run worker if in pause mode.
  -m | --memory            The memory limit in megabytes.
  -s | --sleep             Number of seconds to sleep after job run complete.
  -t | --tries             Number of times to attempt a job if it failed.
  --timeout                Number of seconds that a job can run.
```

If you want to run specify queue list, use arguments:
 
```bash
php windwalker queue worker default --tries=3
```

Or run multiple queues with priority.

```bash
php windwalker queue worker high normal mail --tries=3
```

### Memory Control

Please make sure your Job will not store any unnecessary data to global object to prevent memory leak.

You can set memory limit of your worker, if memory exceeded, worker will close self. 

```bash
php windwalker queue worker --memory=512 # MB
```

### Forever Background Running

To make worker always running or restart itself, you can use process control system like [Supervisor](http://supervisord.org/) or
 [forever.js](https://github.com/foreverjs/forever) to monitor your process.
 
```bash
forever -c php windwalker queue worker --tries=3 
```

### Restart

After you modified your code, you may want to all background workers restart to use new code, use `restart` to send signal to all works:

```bash
php windwalker queue restart
```

### Pause Mode

If you set site offline by `windwalker system down`, all workers will pause but not closed. After you set site online by `system up`,
workers will continue work. You are allow to use `--force` to ignore pause mode:

```bash
php windwalker queue worker --force
```

## Send Mail in Jobs

Windwalker separate web and console as two different environment, by default, many services which web loaded has not been loaded in console.
If you write a Job like this:

```php
class HelloJob implements JobInterface
{
	public function getName()
	{
		return 'hello';
	}

	public function execute()
	{
		$message = Mailer::createMessage()
			->subject('Hello Mail')
			->to('hello@windwalker.io')
			->renderBody('hello', [], 'edge', 'flower');

		Mailer::send($message);
	}
}
```

You may get some error messages:

- Key `widget.manager` has not registered in container.
- Key `uri` has not registered in container.
- Key `router` has not registered in container.

And more...

A solution is that generate `MailMessage` before job create and inject it, queue service will serialize and handle them later.

```php
class HelloJob implements JobInterface
{
	protected $message;

	public function __construct(MailMessage $message)
	{
		$this->message = $message;
	}

	public function getName()
	{
		return 'hello';
	}

	public function execute()
	{
		Mailer::send($this->message);
	}
}
```

Push job:

```php
$message = Mailer::createMessage()
    ->subject('Hello Mail')
    ->to('hello@windwalker.io')
    ->renderBody('hello', [], 'edge', 'flower');

$queue->push(new HelloJob($message))
```

Now since HelloJob only use Mail service without any other services, so it will work perfect. 

But if you still need to generate MailMessage and mail body in Job, Windwalker provides a helper to implement it:

```php
use Windwalker\Core\Console\ConsoleHelper;

class HelloJob implements JobInterface
{
	public function getName()
	{
		return 'hello';
	}

	public function execute()
	{
	    ConsoleHelper::prepareWebEnvironment(
            'web',
            'http://domain.com/windwalker/www/dev.php/flower/sakuras',
            'windwalker/www/dev.php'
        );
	
		$message = Mailer::createMessage()
			->subject('Hello Mail')
			->to('hello@windwalker.io')
			->renderBody('hello', [], 'edge', 'flower');

		Mailer::send($message);
	}
}
```

Use `ConsoleHelper`, it will help us prepare some important environment of web, you must provide 3 arguments:

- `env` --> Use `web` or `dev`, Windwalker will load config about this env.
- `url` --> Create a URL to simulate web request.
- `script` --> The php script name start from DocumentRoot, The URL path after it will be route.

Now you can use `$uri : UriData` in your widget or view template to get [uri information](../mvc/uri-route-building.html#use-uridata-in-view). 
You can also use `$router` service to [build route](../mvc/uri-route-building.html#build-route).

If your route not found, maybe console environment didn't load your route settings, load it by `ConsoleHelper`:

```php
ConsoleHelper::prepareWebEnvironment(
    'web',
    'http://domain.com/windwalker/www/dev.php/flower/sakuras',
    'windwalker/www/dev.php'
    [
        WINDWALKER_ETC . '/routing.yml'
    ]
);
```

## Failed Jobs

If jobs failed, you may want to log them in a place and retry later or check the error message. Windwalker provides a
database driven failed handler. Please run this command to create migration file:

```bash
php windwalker queue failed-table
```

After you migrate it, all failed jobs will store in `queue_failed_jobs` table. Use `retry {ID}` to retry a job:

```bash
php windwalker queue retry 3
```

Or retry all:

```bash
php windwalker queue retry --all
```

Delete failed jobs:

```bash
php windwalker queue remove-failed 3

php windwalker queue remove-failed --all
```

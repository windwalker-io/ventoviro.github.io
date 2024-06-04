---
layout: doc
title: Introduction
component: promise
---

# Introduction

This document is work in process.

## Installation

Install via composer

```bash
composer require windwalker/promise ^4.0
```

## Use in Windwalker

To utilize any component supporting asynchronous calls, a promise will be returned as the value:

```php
// Http Client
$http = new \Windwalker\Http\HttpClient();

$http->postAsync(...)->then(fn () => ...);

// File Async operation
$file = \Windwalker\fs('/path/to/file');

$file->deleteAsync()->then(fn () => ...);
```

## Use as Standalone Component

Simply initiate Promise object which like ES syntax.

```php
use Windwalker\Promise\Promise;

$promise = new Promise(function ($resolve, $reject) {
    $resolve(...);
});

$promise->wait();
```

## Getting Started

### How Promise Works in Pure PHP

PHP is inherently synchronous. When run in an Apache module, FPM, or CGI, there are no asynchronous processes.

Windwalker Promise creates a queue to manage all your asynchronous tasks. It waits until the PHP process ends (for example, when the process shuts down or runs to completion) and then executes all the tasks.

For instance, the following code attempts to send mail asynchronously. Once `runAndSendMail()` completes, the promise tasks are added to a global queue and not executed immediately. These tasks will be performed after the entire process ends, preventing the user from experiencing any delay when the system tries to send an email.

```php
use Windwalker\Promise\Promise;

function runAndSendMail() {
    // Do something
    
    // Start to send mail
    return Promise::resolved()
        ->then(function () {
            // This will run after the process ends
            Mailer::send(...);
        });
}

runAndSendMail()->then(...);
```

### Force Wait

To force all asynchronous tasks to run, you can use `wait()` to make PHP pause and block:

```php
$promise = runAndSendMail();

// This will process all tasks and block
$promise->wait();

// After all tasks executed, the process will resume
```


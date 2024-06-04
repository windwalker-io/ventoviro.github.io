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

Use any component which is support async calling, the promise will be return value:

```php
// Http Client
$http = new \Windwalker\Http\HttpClient();

$http->postAsync(...)->then(fn () => ...);

// File Async operation
$file = \Windwalker\fs('/path/to/file');

$file->deleteAsync()->then(fn () => ...);
```

## Use as Standalone Component

```php
use Windwalker\Promise\Promise;

$promise = new Promise(function ($resolve, $reject) {
    $resolve(...);
});

$promise->wait();
```

## Getting Started

### How Promise Works in Pure PHP

PHP is synchronous, if we run PHP in Apache module, FPM or CGI, there won't be any asynchronous process.

Windwalker Promise makes a queue to store all your async tasks, it will wait until PHP process end (For example, when the process shutdown or running to the end), and run the all tasks.

For example, below codes trying to send mail asynchronous, when `runAndSendMail()` completed, the promise then task will push to a global task queue and not running instantly, this task will be executed after whole process end, so user won't experience a block when system trying to send mail. 

```php
use Windwalker\Promise\Promise;

function runAndSendMail() {
    // Do something
    
    // Start to send mail
    return Promise::resolved()
        ->then(function () {
            // This will run after process end
            Mailer::send(...);
        });
}

runAndSendMail()->then(...);
```

### Force Wait

If you want to force all async tasks run, you may use `wait()` to make PHP wait and block:

```php
$promise = runAndSendMail();

// This will run through all tasks and block
$promise->wait();

// After all tasks executed, process will resume
```


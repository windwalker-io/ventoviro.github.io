layout: documentation.twig
title: Events

---

# Event Listener (Observer) Pattern

The observer pattern is a software design pattern in which an object, called the subject, maintains a list of its dependents, 
called observers, and notifies them automatically of any state changes, usually by calling one of their methods. 

See: [Observer pattern on Wikipedia](http://en.wikipedia.org/wiki/Observer_pattern)

## The Flow

First, there will be many listeners(observers), we can attach them to a dispatcher object.

![p-2015-01-01-8](https://cloud.githubusercontent.com/assets/1639206/5592361/7a569cdc-9206-11e4-829b-846b2c31557e.jpg)

Now, all listeners listened this dispatcher, if some event triggered, this dispatcher will notify all listeners.

![p-2015-01-01-9](https://cloud.githubusercontent.com/assets/1639206/5592360/7a4ed9c0-9206-11e4-91aa-d3d50c4388c5.jpg)

So, if any listener has the method which matched the event, dispatcher will call this method.

# Start Using Event

Create an event object named `onBeforeContentSave`, and set some arguments.

``` php
use Windwalker\Event\Event;

$event = new Event('onBeforeContentSave');

$content = new stdClass;

$event->setArgument('title', 'My content');
$event->setArgument('content', $content);
```

Create your listener:

``` php
use Windwalker\Event\EventInterface;

class ContentListener
{
    public function onBeforeContentSave(EventInterface $event)
    {
        $event->getArgument('content')->title = $event->getArgument('title'); 
    }
    
     public function onAfterContentSave(EventInterface $event)
    {
        // Do something
    }
}
```

Add listener to Dispatcher:

``` php
$dispatcher = \Windwalker\Ioc::getDispatcher();

// OR

$dispatcher = $container->get('system.dispatcher');

// Attach listener
$dispatcher->addListener(new ContentListener);
```

Then we trigger the event we created:

``` php
// Trigger the onBeforeContentSave
$dispatcher->triggerEvent($event);

// ContentListener::onBeforeContentSave will set title into $content object.
$content->title == 'My content';
```

If a method name in listener equals to event name, Dispatcher will run this method and inject Event into this method.
Then we can do many things we want.

## Array Access

Event can access like array:

``` php
// Set
$event['data'] = $data;

// Get
$data = $event['data'];
```

# Listeners

There can be two types of listeners, using class or closure.

## Class Listeners

Using class, just new an instance

``` php
$dispatcher->addListener(new ContentListener);
```

You may provides priority for every methods.

``` php
use Windwalker\Event\ListenerPriority;

// Add priorities
$dispatcher->addListener(
    new ContentListener,
    array(
        'onBeforeContentSave' => ListenerPriority::LOW,
        'onAfterContentSave' => ListenerPriority::HIGH
    )
);

// Or using an inner method to get all methods
$dispatcher->addListener(new ContentListener, ContentListener::getPriorities());
```

## Closure Listeners

If using closure, you must provide the priority and an event name to listen.

``` php
$dispatcher->addListener(
    function (EventInterface $event)
    {
        // Do something
    }, 
    array('onContentSave' => ListenerPriority::NORMAL)
);
```

# Dispatcher

## Trigger An Event Object

This is the most normal way to trigger an event.

``` php
$event = new Event('onFlowerBloom');

$event->setArgument('flower', 'sakura');

$dispatcher->triggerEvent($event);
```

Add arguments when triggering event, the arguments will merge with previous arguments you set.

``` php
$args = array(
    'foo' => 'bar'
);

$dispatcher->triggerEvent($event, $args);
```

## Add An Event Then Trigger It Later

We can add an event into Dispatcher, then use event name to raise it laster.

``` php
$event = new Event('onFlowerBloom');

$event->setArgument('flower', 'sakura');

$dispatcher->addEvent($event);

// Nothing happen

$dispatcher->triggerEvent('onFlowerBloom');
```

## Trigger A New Event Instantly

We don't need create event first, just trigger a string as event name, Dispatcher will create an event instantly.

``` php
$args = array(
    'foo' => 'bar'
);

$dispatcher->triggerEvent('onCloudMoving', $args);
```

# Stopping Event

If you stop an event, the next listeners in the queue won't be called.

``` php
class ContentListener
{
    public function onBeforeContentSave(EventInterface $event)
    {
        // Stopping the Event propagation.
        $event->stop();
    }
}
```

# DispatcherAwareInterface and Trait

In PHP 5.4 or higher, you can use `DispatcherAwareTrait`.

``` php
use Windwalker\Event\DispatcherAwareInterface;
use Windwalker\Event\DispatcherAwareTrait;

class Application implements DispatcherAwareInterface
{
    use DispatcherAwareTrait;
    
    // ...
}
```

# Core Events

- onBeforeInitialise
- onAfterInitialise
- onBeforeExecute
- onAfterExecute
- onBeforeRouting
- onAfterRouting
- onBeforeRender
- onAfterRender
- onBeforeRespond
- onAfterRespond
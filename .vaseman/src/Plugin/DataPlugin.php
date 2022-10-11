<?php

namespace App\Plugin;

use App\Event\AfterProcessEvent;
use App\Event\BeforeProcessEvent;
use App\Event\DataProvideEvent;
use Windwalker\Event\Attributes\EventSubscriber;
use Windwalker\Event\Attributes\ListenTo;

#[EventSubscriber]
class DataPlugin
{
    use DataLoaderTrait;

    #[ListenTo(DataProvideEvent::class)]
    public function dataProvider(DataProvideEvent $event): void
    {
        $data = &$event->getData();

        $menu = include __DIR__ . '/../../resources/data/doc.php';

        $data['docTree'] = $menu;
    }

    #[ListenTo(BeforeProcessEvent::class)]
    public function beforeProcess(BeforeProcessEvent $event): void
    {
        //
    }

    #[ListenTo(AfterProcessEvent::class)]
    public function afterProcess(AfterProcessEvent $event): void
    {
        //
    }
}

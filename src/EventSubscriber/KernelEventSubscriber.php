<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventSubscriber implements EventSubscriberInterface
{
    public function onKernalException(ExceptionEvent $exceptionEvent)
    {
        $exception = $exceptionEvent->getThrowable();
        var_dump($exception->getMessage());
        exit();

    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernalException'];
    }
}
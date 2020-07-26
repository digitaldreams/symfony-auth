<?php

namespace App\EventSubscriber;

use App\Events\UserRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegistered',
        ];
    }

    /**
     * @param \App\Events\UserRegisteredEvent $event
     */
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $this->logger->info('New user registered as ' . $event->getUser()->getName());
    }

}

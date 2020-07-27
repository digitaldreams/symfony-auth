<?php

namespace App\EventSubscriber;

use App\Events\UserRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserRegisterSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Symfony\Component\Mailer\MailerInterface
     */
    protected $mailer;

    /**
     * @var \Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface
     */
    protected $param;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, ContainerBagInterface $param)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->param = $param;
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
     *
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();

        $email = (new Email())
            ->from($this->param->get('app.from_email'))
            ->to($this->param->get('app.admin_email'))
            ->subject('New User ' . $user->getName() . ' registered')
            ->text('New user registered as ' . $user->getUsername())
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);
    }

}

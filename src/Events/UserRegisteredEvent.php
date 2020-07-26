<?php


namespace App\Events;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    /**
     * @var User
     */
    protected User $user;

    /**
     *
     */
    public const NAME = 'user.registered';

    /**
     * UserRegisteredEvent constructor.
     *
     * @param \App\Entity\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \App\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}
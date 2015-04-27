<?php

namespace AppBundle\Event;

use AppBundle\Model\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class EmailChangeEvent.php
 * @package AppBundle\Event
 * @author Marcin Bonk <marvcin@gmail.com>
 */
class EmailChangeEvent extends Event
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
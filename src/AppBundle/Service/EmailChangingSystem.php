<?php

namespace AppBundle\Service;

use AppBundle\Model\User;
use AppBundle\Event\EmailChangeEvent;
use AppBundle\Event\ServiceEvents;
use AppBundle\Exception\EmailChangeException;
use AppBundle\Model\UserRepository;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcherInterface;

/**
 * Class EmailChangingSystem.php
 * @package AppBundle\Service
 * @author Marcin Bonk <marvcin@gmail.com>
 */
class EmailChangingSystem
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var TraceableEventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param UserRepository $repository
     * @param TraceableEventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserRepository $repository, TraceableEventDispatcherInterface $eventDispatcher)
    {
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $from
     * @param string $to
     * @throws EmailChangeException
     */
    public function changeEmail($from, $to)
    {
        try {
            $this->validateEmail($from);
            $this->validateEmail($to);
        } catch (\LogicException $exception) {
            $this->throwEmailException($exception->getMessage(), 0, $exception);
        }
        if (null !== $this->repository->findUserByEmail($to)) {
            $this->throwEmailException(sprintf('User with email "%s" already exist.', $to));
        }

        $user = $this->repository->findUserByEmail($from);
        if (null === $user) {
            $this->throwEmailException(sprintf('User with email "%s" not found.', $from));
        }
        $user->setEmail($to);
        $this->notifyEmailChanged($user);
    }

    /**
     * @param User $user
     * @return $this
     */
    protected function notifyEmailChanged(User $user)
    {
        $event = new EmailChangeEvent();
        $event->setUser($user);
        $this->eventDispatcher->dispatch(ServiceEvents::EMAIL_CHANGE_EVENT, $event);

        return $this;
    }

    /**
     * @param $email
     * @return bool
     * @throws \LogicException
     */
    protected function validateEmail($email)
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \LogicException(
                sprintf(
                    'Variable "%s" is not valid email string.',
                    $email
                )
            );
        }

        return true;
    }

    /**
     * @param $message
     * @param int $code
     * @param \Exception $exception
     * @throws EmailChangeException
     */
    protected function throwEmailException($message, $code = 0, \Exception $exception = null)
    {
        throw new EmailChangeException($message, $code, $exception);
    }
}
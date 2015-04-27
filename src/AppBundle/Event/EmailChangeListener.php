<?php

namespace AppBundle\Event;

use AppBundle\Service\MarketingSystem;
use AppBundle\Service\StatsSystem;
use Monolog\Logger;

/**
 * Class EmailChangeListener.php
 * @package AppBundle\Event
 * @author Marcin Bonk <marvcin@gmail.com>
 */
class EmailChangeListener
{
    /**
     * @var StatsSystem
     */
    protected $statsSystem;

    /**
     * @var MarketingSystem
     */
    protected $marketingSystem;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param StatsSystem $statsSystem
     * @param MarketingSystem $marketingSystem
     * @param Logger $logger
     */
    public function __construct(StatsSystem $statsSystem, MarketingSystem $marketingSystem, Logger $logger)
    {
        $this->statsSystem = $statsSystem;
        $this->marketingSystem = $marketingSystem;
        $this->logger = $logger;
    }


    /**
     * @param EmailChangeEvent $event
     */
    public function onEmailChange(EmailChangeEvent $event)
    {
        $user = $event->getUser();
        $notification = ['action' => 'email_change', 'user' => $user];

        $this->logger->log(Logger::DEBUG, 'Email changed for user.', [serialize($user)]);
        $this->marketingSystem->postRequest($notification);
        $this->statsSystem->postRequest($notification);
    }
}
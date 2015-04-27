<?php

namespace AppBundle\Service;

/**
* interface NotificationableInterface.php
* @package AppBundle\Service
* @author Marcin Bonk <marvcin@gmail.com>
*/
interface NotificationableInterface
{
    /**
     * @param $data
     * @return bool
     */
    public function postRequest($data);
}
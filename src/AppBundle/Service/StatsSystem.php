<?php
/**
 * Created by PhpStorm.
 * User: Radek
 * Date: 06/01/15
 * Time: 22:14
 */

namespace AppBundle\Service;


class StatsSystem implements NotificationableInterface
{
	public function postRequest($data)
	{
		return true;
	}
}
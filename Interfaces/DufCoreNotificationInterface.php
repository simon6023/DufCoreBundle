<?php
namespace Duf\CoreBundle\Interfaces;

interface DufCoreNotificationInterface
{
	public function processNotification($notification_type, $listener_data, $user = null);
	public function getNotifications($user, array $notification_types = null, $read_status = null);
}
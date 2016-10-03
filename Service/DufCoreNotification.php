<?php
namespace Duf\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Doctrine\ORM\EntityManager as EntityManager;

use Duf\CoreBundle\Entity\DufCoreNotification as Notification;

class DufCoreNotification
{
    private $em;
    private $container;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em                   = $entityManager;
        $this->container            = $container;
    }

    public function createNotification($notification_type, $notification_data, $user = null)
    {
    	if (null !== ($notification = $this->checkExistingNotification($notification_type, $notification_data, $user))) 
    		return null;

    	$notification 				= new Notification();
    	$notification->setCreatedAt(new \DateTime());
    	$notification->setNotificationType($notification_type);
    	$notification->setNotificationData($notification_data);
    	$notification->setIsRead(false);

    	if (null !== $user)
    		$notification->setCreatedBy($user);

    	$this->em->persist($notification);
    	$this->em->flush();

        return $notification;
    }

    public function getNotificationTypesForListener($listener_type, $event_class, $event_action, $user = null)
    {
    	$notification_types 			= $this->em->getRepository('DufCoreBundle:DufCoreNotificationType')->findBy(
    			array(
    				'enabled' 			=> '1',
    				'listenerType' 		=> $listener_type,
    				'eventClass' 		=> $event_class,
    				'eventAction' 		=> $event_action,
    			),
    			array(
    				'priority' 			=> 'ASC',
    			)
    		);

        // check user's roles
        if (null !== $user && false === $this->filterNotificationTypesByUser($notification_types, $user))
            $notification_types         = $this->filterNotificationTypesByUser($notification_types, $user);

    	return $notification_types;
    }

    public function getDoctrineEvents()
    {
        return $this->em->getRepository('DufCoreBundle:DufCoreNotificationType')->findByDistinctDoctrineEvents();
    }

    public function getDoctrineEntities()
    {
        return $this->em->getRepository('DufCoreBundle:DufCoreNotificationType')->findByDistinctDoctrineEntities();
    }

    private function checkExistingNotification($notification_type, $notification_data, $user)
    {
    	$filters = array(
				'notification_type' 		=> $notification_type->getId(),
				'notification_data' 		=> $notification_data,
    		);

    	if (null !== $user)
    		$filters['created_by'] 			= $user->getId();

    	return $this->em->getRepository('DufCoreBundle:DufCoreNotification')->findByExistingNotification($filters);
    }

    private function filterNotificationTypesByUser($notification_types, $user)
    {
        foreach ($notification_types as $index => $notification_type) {
            if (null !== $user && null !== $notification_type->getAccessLevel()) {
                $role       = $this->em->getRepository('Duf\AdminBundle\Entity\UserRole')->findOneByName($notification_type->getAccessLevel());
                if (empty($role) || $user->hasRole($role)) {
                    unset($notification_types[$index]);
                }
            }
        }

        return $notification_types;
    }

    protected function getEntityManager()
    {
    	return $this->em;
    }

    protected function getContainer()
    {
    	return $this->container;
    }

    protected function getNotificationTypes($enabled = true)
    {
     	$notification_types 			= $this->em->getRepository('DufCoreBundle:DufCoreNotificationType')->findBy(
    			array(
    				'enabled' 			=> $enabled,
    			),
    			array(
    				'priority' 			=> 'ASC',
    			)
    		);

    	return $notification_types;
    }

    protected function getQueryBuilder()
    {
    	return $this->em->createQueryBuilder();
    }

    protected function getReadStatusForQuery($read_status)
    {
    	return (true === $read_status) ? '1' : '0' ;
    }
}
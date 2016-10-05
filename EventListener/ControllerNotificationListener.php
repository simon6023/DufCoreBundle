<?php
namespace Duf\CoreBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager as EntityManager;

use Duf\CoreBundle\Service\DufCoreNotification;

class ControllerNotificationListener
{
	protected $notification_service;
	protected $em;
	protected $container;
	protected $token_storage;

	public function __construct(DufCoreNotification $notification_service, EntityManager $entityManager, Container $container, TokenStorage $token_storage)
	{
		$this->notification_service = $notification_service;
		$this->em                   = $entityManager;
		$this->container            = $container;
		$this->token_storage 		= $token_storage;
	}

	public function listenControllerNotifications(FilterResponseEvent $event)
	{
		if (!$event->isMasterRequest())
			return;

		$request_controller 	= explode('::', $event->getRequest()->attributes->get('_controller'));

		if (!isset($request_controller[1]))
			return;

		$token 					= $this->token_storage->getToken();
		$user 					= (null !== $token) ? $token->getUser() : null;
		$controller_class 		= $request_controller[0];
		$controller_action 		= $request_controller[1];
		$notification_types 	= $this->notification_service->getNotificationTypesForListener('controller', $controller_class, $controller_action, $user);

		if (empty($notification_types))
			return;

		foreach ($notification_types as $notification_type) {
			$target_class 	= $notification_type->getTargetClass();
			$target_class 	= new $target_class($this->em, $this->container);
			$target_class->processNotification($notification_type, $event->getRequest(), $user);
		}
	}
}
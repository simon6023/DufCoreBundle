<?php
namespace Duf\CoreBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class DoctrineNotificationSubscriber implements EventSubscriber
{
	protected $container;

	public function __construct(Container $container)
	{
		$this->container            = $container;
	}

	public function getSubscribedEvents()
	{
		return array(
				'postPersist',
				'postUpdate',
				'postDelete'
			);
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$this->index($args, 'postPersist');
	}

	public function postUpdate(LifecycleEventArgs $args)
	{
		$this->index($args, 'postUpdate');
	}

	public function postDelete(LifecycleEventArgs $args)
	{
		$this->index($args, 'postDelete');
	}

	public function index(LifecycleEventArgs $args, $action)
    {
    	$token 						= $this->container->get('security.token_storage')->getToken();
    	$user 						= (null !== $token && null !== $token->getUser()) ? $token->getUser() : null;
    	$em 						= $args->getEntityManager();
        $entity 					= $args->getEntity();
        $notification_types 		= $this->container->get('duf_core.dufcorenotification')->getNotificationTypesForListener('doctrine', get_class($entity), $action);

        foreach ($notification_types as $notification_type) {
			$target_class 	= $notification_type->getTargetClass();
			$target_class 	= new $target_class($em, $this->container);
			$target_class->processNotification($notification_type, $entity, $user);
        }
    }
}
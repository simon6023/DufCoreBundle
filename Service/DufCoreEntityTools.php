<?php
namespace Duf\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Doctrine\ORM\EntityManager as EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;

class DufCoreEntityTools
{
    private $em;
    private $container;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em                   = $entityManager;
        $this->container            = $container;
    }

    public function getEntityGetter($entity, $property)
    {
    	if (is_string($entity)) {
    		$entity 			= new $entity;
    	}

        // get parent entity methods
        $entity_methods 	= get_class_methods($entity);

        foreach ($entity_methods as $method) {
        	if (strpos($method, 'get') !== false || strpos($method, 'add') !== false) {
	            $method_check 	= str_replace('get', '', $method);
	            $method_check 	= str_replace('add', '', $method_check);

	            if (strtolower($method_check) == $property)
                    return $method;

                // remove underscores
                $method_check   = str_replace('_', '', $method_check);

                if (strtolower($method_check) == $property)
                    return $method;
        	}
        }

        return null;
    }

    public function getEntitySetter($entity, $property, $setter_method= 'set')
    {
        if (is_string($entity)) {
            $entity             = new $entity;
        }

        // get parent entity methods
        $entity_methods     = get_class_methods($entity);

        foreach ($entity_methods as $method) {
            if (strpos($method, $setter_method) !== false) {               
                $method_check   = str_replace('set', '', $method);
                $method_check   = str_replace('add', '', $method_check);

                if (strtolower($method_check) == $property) {
                    return $method;
                }

                // check plural for method
                $method_check = rtrim($method_check, 's');
                if (strtolower($method_check) == $property) {
                    return $method;
                }

                // check plural for property
                $property = rtrim($property, 's');
                if (strtolower($method_check) == $property) {
                    return $method;
                }
            }
        }

        return null;
    }

    public function isEntity($class)
    {
        if (class_exists($class)) {
            if (is_object($class)) {
                $class = ClassUtils::getClass($class);
            }

            return ! $this->em->getMetadataFactory()->isTransient($class);
        }

        return false;
    }
}

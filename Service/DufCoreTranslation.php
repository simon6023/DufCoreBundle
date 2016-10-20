<?php
namespace Duf\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

use Doctrine\ORM\EntityManager as EntityManager;

class DufCoreTranslation
{
    private $em;
    private $container;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em                   = $entityManager;
        $this->container            = $container;
    }

    public function getAvailableLanguages($keys_only = false)
    {
        $default_locale     = $this->container->getParameter('locale');

        // check if Duf\AdminBundle\Entity\Language entity exists
        if ($this->container->get('duf_core.dufcoreentitytools')->isEntity('Duf\AdminBundle\Entity\Language')) {
            $_languages = $this->em->getRepository('DufAdminBundle:Language')->findBy(
                    array(
                        'enabled'   => true,
                    )
                );

            $languages = array();

            foreach ($_languages as $language) {
                if ($keys_only) {
                    $languages[] = $language->getCode();
                }
                else {
                    $languages[] = $language;
                }
            }

            return $languages;
        }

        return $default_locale;
    }

    public function findEntityTranslatable($params)
    {
        if (!isset($params['entity_name']))
            return null;

        $routing_service    = $this->container->get('duf_admin.dufadminrouting');
        $locale             = (isset($params['locale'])) ? $params['locale'] : $this->container->getParameter('locale');
        $entity_class       = (strpos($params['entity_name'], ':') !== false) ? $routing_service->getEntityClass($params['entity_name']) : $params['entity_name'];

        if (substr($entity_class, 0, 1) == '\\')
            $entity_class = substr($entity_class, 1, strlen($entity_class));

        // get translations for entity
        $translations   = $this->em->getRepository('Gedmo\Translatable\Entity\Translation')->findBy(
                array(
                    'objectClass'      => $entity_class,
                    'field'            => $params['search_field'],
                    'content'          => $params['search_value'],
                )
            );

        if (!empty($translations)) {
            $entity_tools               = $this->container->get('duf_core.dufcoreentitytools');
            $translated_entity_id       = current($translations)->getForeignKey();
            $translated_entity          = $this->em->getRepository($entity_class)->findOneById($translated_entity_id);

            if (!empty($translated_entity)) {
                foreach ($translations as $translation) {
                    // get entity setter
                    $setter         = $entity_tools->getEntitySetter($translated_entity, $translation->getField());

                    if (null !== $setter) {
                        $translated_entity->{$setter} = $translation->getContent();
                    }
                }

                return $translated_entity;
            }
        }

        return null;
    }
}
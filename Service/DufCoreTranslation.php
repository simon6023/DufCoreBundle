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
}
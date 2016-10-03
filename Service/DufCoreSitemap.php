<?php
namespace Duf\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Doctrine\ORM\EntityManager as EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;

class DufCoreSitemap
{
    private $em;
    private $container;
    private $router;

    public function __construct(EntityManager $entityManager, Container $container, Router $router)
    {
        $this->em                   = $entityManager;
        $this->container            = $container;
        $this->router               = $router;
    }

    public function getUrls($entity_name = null)
    {
        $urls       = array();
        $entities   = $this->getSitemapableEntities();

        if (!empty($entities)) {
            foreach ($entities as $entity) {
                if (null !== $entity_name) {
                    if (strtolower($entity['entity_infos']['name']) == $entity_name) {
                        $urls_array     = $this->getXml($entity);
                        if (isset($urls_array[$entity_name])) {
                            $urls = $urls_array[$entity_name];
                        }
                    }
                }
                else {
                    $urls[]     = $this->getXml($entity);
                }
            }
        }

        return $urls;
    }

    public function getStaticUrls()
    {
        $routes     = $this->container->get('duf_core.dufcoreconfig')->getDufCoreConfig('static_urls');
        $urls       = array();
        $lastmod    = new \DateTime();

        if (!empty($routes)) {
            foreach ($routes as $route_name) {
                $urls[] = array(
                        'loc'           => $this->router->generate($route_name, array(), UrlGeneratorInterface::ABSOLUTE_URL),
                        'lastmod'       => $lastmod->format('Y-m-d'),
                        'priority'      => '1.0',
                        'changefreq'    => 'weekly',
                    );
            }
        }

        return $urls;
    }

    public function getMainUrls()
    {
        $urls       = $this->getUrls();
        $main_urls  = array();
        $lastmod    = new \DateTime();

        foreach ($urls as $urlset) {
            $urlset_name    = array_keys($urlset);

            $main_urls[]    = array(
                    'loc'       => $this->router->generate('duf_core_sitemap_file', array('entity_name' => $urlset_name[0]), UrlGeneratorInterface::ABSOLUTE_URL),
                    'lastmod'   => $lastmod->format('Y-m-d'),
                );
        }

        $static_urls = $this->getStaticUrls();
        if (!empty($static_urls)) {
            $main_urls[] = array(
                    'loc'       => $this->router->generate('duf_core_sitemap_statics', array(), UrlGeneratorInterface::ABSOLUTE_URL),
                    'lastmod'   => $lastmod->format('Y-m-d'),
                );
        }

        return $main_urls;
    }

    private function getXml($entity_infos)
    {
        $xml_results    = array();
        $entity_name    = $entity_infos['entity_infos']['bundle'] . ':' . $entity_infos['entity_infos']['name'];
        $default_locale = $this->container->getParameter('locale');

        if (empty($entity_infos['find_by'])) {
            $entities       = $this->em->getRepository($entity_name)->findAll();
        }
        else {
            $filters        = $entity_infos['find_by'];
            $entities       = $this->em->getRepository($entity_name)->findBy($filters);
        }

        foreach ($entities as $entity) {
            // get parameters
            $params         = $this->getParamsForEntity($entity_infos['route_params'], $entity);

            if (false !== $params) {
                $loc            = $this->router->generate($entity_infos['route'], $params, UrlGeneratorInterface::ABSOLUTE_URL);
                $lastmod        = $this->getEntityLastMod($entity);
                $xml_filename   = strtolower($entity_infos['entity_infos']['name']);

                $xml_array = array(
                        'loc'           => $loc,
                        'lastmod'       => $lastmod->format('Y-m-d'),
                        'priority'      => $entity_infos['priority'],
                        'changefreq'    => $entity_infos['changefreq'],
                    );

                // get entity translations
                $entity_translations      = $this->em->getRepository('Gedmo\Translatable\Entity\Translation')->findTranslations($entity);

                foreach ($entity_translations as $lang_code => $translations) {
                    if ($lang_code !== $default_locale) {
                        $translation_params             = $this->getParamsForEntity($entity_infos['route_params'], $entity, $translations);
                        $translation_params['_locale']  = $lang_code;

                        foreach ($translation_params as $translation_param_name => $translation_param_value) {
                            if (isset($translations[$translation_param_name]) && !empty($translations[$translation_param_name])) {
                                if (false !== $translation_params) {
                                    $translation_loc            = $this->router->generate($entity_infos['route'], $translation_params, UrlGeneratorInterface::ABSOLUTE_URL);

                                    $xml_array['translations'][] = array(
                                            'hreflang'  => $lang_code,
                                            'href'      => $translation_loc,
                                        );
                                }
                            }
                        }
                    }
                }

                if (!empty($xml_array['translations'])) {
                    $xml_array['translations'][] = array(
                            'hreflang'      => $default_locale,
                            'href'          => $loc,
                        );
                }

                $xml_array['default_locale']    = $default_locale;
                $xml_results[$xml_filename][]   = $xml_array;
            }
        }

        return $xml_results;
    }

    private function getSitemapableEntities()
    {
        $_entities                  = $this->getAllEntities();
        $annotationReader           = new AnnotationReader();
        $entities                   = array();

        foreach ($_entities as $entity) {
            if (!empty($entity['bundle'])) {
                $class_name     = $entity['namespace'] . '\\' . $entity['name'];

                // get entity annotations
                $reflectionClass    = new \ReflectionClass($class_name);
                $annotations        = $annotationReader->getClassAnnotations($reflectionClass);

                foreach ($annotations as $annotation) {
                    if (get_class($annotation) == 'Duf\CoreBundle\Annotations\SitemapableAnnotation') {
                        $entities[] = array(
                                'entity_infos'      => $entity,
                                'route'             => $annotation->route,
                                'route_params'      => $annotation->route_params,
                                'changefreq'        => $annotation->changefreq,
                                'priority'          => $annotation->priority,
                                'find_by'           => $annotation->find_by,
                            );
                    }
                }
            }
        }

        return $entities;
    }

    private function getAllEntities()
    {
        $meta       = $this->em->getMetadataFactory()->getAllMetadata();

        foreach ($meta as $m) {
            $namespace      = $m->namespace;
            $name           = str_replace($namespace . '\\', '', $m->getName());
            $entity_pieces  = explode('\\', $namespace);
            $bundle         = '';

            foreach ($entity_pieces as $entity_piece) {
                if (strpos($entity_piece, 'Bundle') !== false) {
                    $bundle = $entity_piece;
                }
            }

            $entities[] = array(
                    'name'          => $name,
                    'namespace'     => $namespace,
                    'bundle'        => $bundle,
                    'repository'    => $m->customRepositoryClassName,
                );
        }

        return $entities;
    }

    private function getParamsForEntity($route_params, $entity, $translations = null)
    {
        $params         = array();
        $entity_tools   = $this->container->get('duf_core.dufcoreentitytools');

        foreach ($route_params as $param_name => $class_property) {
            // get property getter
            $getter         = $entity_tools->getEntityGetter($entity, $class_property);

            if (null !== $getter) {
                if (null !== $translations && isset($translations[$class_property])) {
                    $param_value            = $translations[$class_property];
                }
                else {
                    $param_value            = $entity->{$getter}();
                }
                
                if (null !== $param_value && strlen($param_value) > 0) {
                    $params[$param_name]    = $param_value;
                }
                else {
                    return false;
                }
            }
        }

        return $params;
    }

    private function getEntityLastMod($entity)
    {
        $lastmod = (method_exists($entity, 'getUpdatedAt')) ? $entity->getUpdatedAt() : new \DateTime() ;

        if (get_class($lastmod) !== 'DateTime') {
            $lastmod    = new \DateTime();
        }

        return $lastmod;
    }
}
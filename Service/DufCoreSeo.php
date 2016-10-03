<?php
namespace Duf\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Doctrine\ORM\EntityManager as EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;

use Duf\AdminBundle\Form\Type\DufAdminTextType;
use Duf\AdminBundle\Form\Type\DufAdminTranslatableTextType;
use Duf\AdminBundle\Form\Type\DufAdminTranslatableTextareaType;
use Duf\CoreBundle\Entity\DufCoreSeo as DufCoreSeoEntity;

class DufCoreSeo
{
    private $em;
    private $container;
    private $fields;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em                   = $entityManager;
        $this->container            = $container;
        $this->fields               = array(
                                        array(
                                            'name'      => 'seo_title',
                                            'type'      => DufAdminTextType::class,
                                            'options'   => array(
                                                'mapped'        => false,
                                                'required'      => false,
                                                'label'         => 'Title Tag',
                                            ),
                                        ),
                                        array(
                                            'name'      => 'seo_meta_desc',
                                            'type'      => TextareaType::class,
                                            'options'   => array(
                                                'mapped'        => false,
                                                'required'      => false,
                                                'label'         => 'Meta Description',
                                                'attr'          => array(
                                                    'class'     => 'no-editor',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'name'      => 'seo_picture',
                                            'type'      => DufAdminTextType::class,
                                            'options'   => array(
                                                'mapped'        => false,
                                                'required'      => false,
                                                'label'         => 'OG Picture',
                                            ),
                                        ),
                                    );
    }

    public function getSeoConfig($entity_name)
    {
        // get entity annotations
        $annotationReader   = new AnnotationReader();
        $reflectionClass    = new \ReflectionClass($entity_name);
        $annotations        = $annotationReader->getClassAnnotations($reflectionClass);

        foreach ($annotations as $annotation) {
            if (get_class($annotation) === 'Duf\CoreBundle\Annotations\SeoAnnotation') {
                return $annotation->params;
            }
        }

        return null;
    }

    public function addSeoFielsToForm($create_form, $form_entity, $entity_class)
    {
        $entity_tools_service   = $this->container->get('duf_core.dufcoreentitytools');

        foreach ($this->fields as $field) {
            $entity_seo         = null;

            if (null !== $form_entity->getId())
                $entity_seo     = $this->getOverridenSeoEntity($entity_class, $form_entity->getId(), $field['name']);

            // add previous value to SEO form field
            if (null !== $entity_seo && !empty($entity_seo) && $field['name'] == $entity_seo->getSeoType())
                $field['options']['data']   = $entity_seo->getSeoValue();

            // check if field is translatable
            if ($this->isTranslatableField($field, $form_entity) && $field['type'] === DufAdminTextType::class)
                $field['type'] = DufAdminTranslatableTextType::class;

            if ($this->isTranslatableField($field, $form_entity) && $field['type'] === TextareaType::class) {
                $field['type'] = DufAdminTranslatableTextareaType::class;
                unset($field['options']['label']);
            }

            $create_form->add($field['name'], $field['type'], $field['options']);
        }

        return $create_form;
    }

    public function getSeoForEntity($entity_name, $entity, $locale)
    {
        $seo                = array();
        $routing_service    = $this->container->get('duf_admin.dufadminrouting');
        $entity_tools       = $this->container->get('duf_core.dufcoreentitytools');
        $entity_class       = $routing_service->getEntityClass($entity_name);

        // check if entity has overriden SEO for each SEO type
        foreach ($this->fields as $field) {
            $entity_seo     = $this->getOverridenSeoEntity($entity_class, $entity->getId(), $field['name'], $locale);

            if (!empty($entity_seo)) {
                $seo[$entity_seo->getSeoType()]      = $this->getHtmlForSeo($entity_seo->getSeoType(), $entity_seo->getSeoValue());
            }
        }

        // if not overriden, get default values
        $annotationReader   = new AnnotationReader();
        $reflectionClass    = new \ReflectionClass($entity_class);
        $annotations        = $annotationReader->getClassAnnotations($reflectionClass);

        foreach ($annotations as $annotation) {
            if (get_class($annotation) === 'Duf\CoreBundle\Annotations\SeoAnnotation') {
                // default title
                if (isset($annotation->default_title) && !empty($annotation->default_title)) {
                    $seo_value      = '';
                    $default_title  = $annotation->default_title;

                    // get parameters
                    $get_params     = preg_match('/%[^%]*%/', $default_title, $parameters);
                    if (isset($parameters) && !empty($parameters)) {
                        foreach ($parameters as $parameter) {
                            $parameter_name         = str_replace('%', '', $parameter);
                            $parameter_value        = $this->container->get('duf_admin.dufadminconfig')->getDufAdminConfig($parameter_name);
                            $default_title          = str_replace($parameter, $parameter_value, $default_title);
                        }
                    }

                    // get variables
                    $get_variables  = preg_match('/(?<!\w)\$\w+/', $default_title, $variables);
                    if (isset($variables) && !empty($variables)) {
                        foreach ($variables as $variable) {
                            $variable_name          = str_replace('$', '', $variable);
                            $getter                 = $entity_tools->getEntityGetter($entity, $variable_name);
                            $variable_value         = $entity->{$getter}();
                            $default_title          = str_replace($variable, $variable_value, $default_title);
                        }
                    }

                    $seo['title']   = $this->getHtmlForSeo('seo_title', $default_title);
                }
            }
        }

        return $seo;
    }

    public function getSeoFields()
    {
        return $this->fields;
    }

    private function getOverridenSeoEntity($entity_class, $entity_id, $seo_type, $locale = null)
    {
        $filters = array(
                'entity_class'  => $entity_class,
                'entity_id'     => $entity_id,
                'seo_type'      => $seo_type,
            );

        if (null !== $locale) {
            $filters['locale']  = $locale;
        }

        $entity_seo     = $this->em->getRepository('DufCoreBundle:DufCoreSeo')->findOneBy($filters);

        return $entity_seo;
    }

    private function isTranslatableField($field, $entity_name)
    {
        // get entity annotations
        $annotationReader   = new AnnotationReader();
        $reflectionClass    = new \ReflectionClass($entity_name);
        $annotations        = $annotationReader->getClassAnnotations($reflectionClass);

        foreach ($annotations as $annotation) {
            if (get_class($annotation) === 'Duf\CoreBundle\Annotations\SeoAnnotation' && isset($annotation->override_translatable)) {
                if (in_array($field['name'], $annotation->override_translatable)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getHtmlForSeo($seo_type, $seo_value)
    {
        switch ($seo_type) {
            case 'seo_title':
                $html   = '<title>' . $seo_value . '</title>';
                break;
            case 'seo_meta_desc':
                $html   = '<meta name="description" content="' . $seo_value . '" />';
                break;
            default:
                $html   = '';
                break;
        }

        return $html;
    }
}
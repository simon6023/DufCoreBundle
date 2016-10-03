<?php

namespace Duf\CoreBundle\Annotations;

/**
 * Annotation for sitemapable entities in DufCoreBundle
 *
 * @author Simon Duflos <simon.duflos@gmail.com>
 *
 * @Annotation
 * @Target("CLASS")
 */
final class SeoAnnotation
{
   /**
     * Parameter params
     *
     * @var array
     */
    public $params;

   /**
     * Parameter override_translatable
     *
     * @var array
     */
    public $override_translatable;

    /**
     * Parameter is_translatable
     *
     * @var boolean
     */
    public $is_translatable;

   /**
     * Parameter default_title
     *
     * @var string
     */
    public $default_title;
}
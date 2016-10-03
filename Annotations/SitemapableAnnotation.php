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
final class SitemapableAnnotation
{
   /**
     * Parameter route
     *
     * @var string
     */
    public $route;

   /**
     * Parameter route_params
     *
     * @var array
     */
    public $route_params;

   /**
     * Parameter find_by
     *
     * @var array
     */
    public $find_by;

   /**
     * Parameter changefreq
     *
     * @var string
     */
    public $changefreq;

   /**
     * Parameter priority
     *
     * @var string
     */
    public $priority;
}
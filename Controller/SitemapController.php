<?php

namespace Duf\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SitemapController extends Controller
{
    public function indexAction()
    {
        $xml            = $this->renderView('DufCoreBundle:Sitemap:index.xml.twig', array(
                'sitemaps'        => $this->get('duf_core.dufcoresitemap')->getMainUrls(),
            )
        );

        return $this->getXmlResponse($xml);
    }

    public function fileAction($entity_name)
    {
        if ($entity_name == 'static_urls') {
            $urls   = $this->get('duf_core.dufcoresitemap')->getStaticUrls();
        }
        else {
            $urls   = $this->get('duf_core.dufcoresitemap')->getUrls($entity_name);
        }

        $xml    = $this->renderView('DufCoreBundle:Sitemap:sitemap-file.xml.twig', array(
                'urls'        => $urls,
            )
        );

        return $this->getXmlResponse($xml);
    }

    private function getXmlResponse($xml)
    {
        $response   = new Response($xml);
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
}

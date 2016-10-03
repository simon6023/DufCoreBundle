<?php

namespace Duf\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;

/**
 * DufCoreSeo
 *
 * @ORM\Table(name="duf_core_seo")
 * @ORM\Entity(repositoryClass="Duf\CoreBundle\Entity\Repository\DufCoreSeoRepository")
 */
class DufCoreSeo extends DufAdminEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="seo_type", type="string", length=255, nullable=false)
     */
    private $seo_type;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_value", type="string", length=255, nullable=false)
     */
    private $seo_value;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_id", type="string", length=10, nullable=false)
     */
     private $entity_id;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_class", type="string", length=200, nullable=false)
     */
     private $entity_class;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5, nullable=true)
     */
    private $locale;

    /**
     * Set seoType
     *
     * @param string $seoType
     *
     * @return DufCoreSeo
     */
    public function setSeoType($seoType)
    {
        $this->seo_type = $seoType;

        return $this;
    }

    /**
     * Get seoType
     *
     * @return string
     */
    public function getSeoType()
    {
        return $this->seo_type;
    }

    /**
     * Set seoValue
     *
     * @param string $seoValue
     *
     * @return DufCoreSeo
     */
    public function setSeoValue($seoValue)
    {
        $this->seo_value = $seoValue;

        return $this;
    }

    /**
     * Get seoValue
     *
     * @return string
     */
    public function getSeoValue()
    {
        return $this->seo_value;
    }

    /**
     * Set entityId
     *
     * @param string $entityId
     *
     * @return DufCoreSeo
     */
    public function setEntityId($entityId)
    {
        $this->entity_id = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return string
     */
    public function getEntityId()
    {
        return $this->entity_id;
    }

    /**
     * Set entityClass
     *
     * @param string $entityClass
     *
     * @return DufCoreSeo
     */
    public function setEntityClass($entityClass)
    {
        $this->entity_class = $entityClass;

        return $this;
    }

    /**
     * Get entityClass
     *
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entity_class;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return DufCoreSeo
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}

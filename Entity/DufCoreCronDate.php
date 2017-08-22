<?php

namespace Duf\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;
use Duf\AdminBundle\Annotations\IndexableAnnotation as Indexable;
use Duf\AdminBundle\Annotations\EditableAnnotation as Editable;

/**
 * DufCoreCronDate
 *
 * @ORM\Table(name="duf_core_cron_date")
 * @ORM\Entity()
 */
class DufCoreCronDate extends DufAdminEntity
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="executed_at", type="datetime", nullable=true)
     */
    public $executed_at;

    /**
     * Set executedAt
     *
     * @param \DateTime $executedAt
     *
     * @return DufCoreCronDate
     */
    public function setExecutedAt($executedAt)
    {
        $this->executed_at = $executedAt;

        return $this;
    }

    /**
     * Get executedAt
     *
     * @return \DateTime
     */
    public function getExecutedAt()
    {
        return $this->executed_at;
    }
}

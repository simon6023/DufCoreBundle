<?php

namespace Duf\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DufCoreCronTaskTrace
 *
 * @ORM\Table(name="duf_core_cron_task_trace")
 * @ORM\Entity(repositoryClass="Duf\CoreBundle\Entity\Repository\DufCoreCronTaskTraceRepository")
 */
class DufCoreCronTaskTrace
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="datetime")
     */
    private $startedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ended_at", type="datetime")
     */
    private $endedAt;

    /**
     * @var float
     *
     * @ORM\Column(name="duration", type="float")
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="Duf\CoreBundle\Entity\DufCoreCronTask")
     * @ORM\JoinColumn(nullable=true)
     */
     protected $cronTask;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startedAt
     *
     * @param \DateTime $startedAt
     *
     * @return DufCoreCronTaskTrace
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt
     *
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set endedAt
     *
     * @param \DateTime $endedAt
     *
     * @return DufCoreCronTaskTrace
     */
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * Get endedAt
     *
     * @return \DateTime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * Set duration
     *
     * @param float $duration
     *
     * @return DufCoreCronTaskTrace
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set cronTask
     *
     * @param \Duf\CoreBundle\Entity\DufCoreCronTask $cronTask
     *
     * @return DufCoreCronTaskTrace
     */
    public function setCronTask(\Duf\CoreBundle\Entity\DufCoreCronTask $cronTask = null)
    {
        $this->cronTask = $cronTask;

        return $this;
    }

    /**
     * Get cronTask
     *
     * @return \Duf\CoreBundle\Entity\DufCoreCronTask
     */
    public function getCronTask()
    {
        return $this->cronTask;
    }
}

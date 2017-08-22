<?php

namespace Duf\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;
use Duf\AdminBundle\Annotations\IndexableAnnotation as Indexable;
use Duf\AdminBundle\Annotations\EditableAnnotation as Editable;

/**
 * DufCoreCronTask
 *
 * @ORM\Table(name="duf_core_cron_task")
 * @ORM\Entity(repositoryClass="Duf\CoreBundle\Entity\Repository\DufCoreCronTaskRepository")
 */
class DufCoreCronTask extends DufAdminEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Indexable(index_column=true, index_column_name="Title")
     * @Editable(is_editable=true, label="Title", required=true, type="text", order=1, placeholder="Write your title")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="command", type="string", length=255)
     * @Indexable(index_column=true, index_column_name="Command")
     * @Editable(is_editable=true, label="Command", required=true, type="text", placeholder="Write your command", order=2)
     */
    private $command;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     * @Indexable(index_column=true, index_column_name="Enabled", boolean_column=true)
     * @Editable(is_editable=true, label="Enabled", required=true, type="checkbox", order=3)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="days", type="text")
     * @Editable(is_editable=true, label="Days", required=true, type="day_picker", order=4)
     */
    private $days;

    /**
     * @var string
     *
     * @ORM\Column(name="hours", type="text")
     * @Editable(is_editable=true, label="Hours", required=true, type="hour_picker", order=5)
     */
    private $hours;

    /**
     * @var string
     *
     * @ORM\Column(name="minutes", type="text")
     * @Editable(is_editable=true, label="Minutes", required=true, type="minute_picker", order=6)
     */
    private $minutes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="executed_at", type="datetime", nullable=true)
     * @Indexable(index_column=true, index_column_name="Last Executed")
     */
    public $executed_at;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return DufCoreCronTask
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set command
     *
     * @param string $command
     *
     * @return DufCoreCronTask
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return DufCoreCronTask
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set days
     *
     * @param string $days
     *
     * @return DufCoreCronTask
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return string
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set hours
     *
     * @param string $hours
     *
     * @return DufCoreCronTask
     */
    public function setHours($hours)
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * Get hours
     *
     * @return string
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Set minutes
     *
     * @param string $minutes
     *
     * @return DufCoreCronTask
     */
    public function setMinutes($minutes)
    {
        $this->minutes = $minutes;

        return $this;
    }

    /**
     * Get minutes
     *
     * @return string
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

    /**
     * Set executedAt
     *
     * @param \DateTime $executedAt
     *
     * @return DufCoreCronTask
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

<?php

namespace Duf\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;

use Duf\AdminBundle\Annotations\IndexableAnnotation as Indexable;
use Duf\AdminBundle\Annotations\EditableAnnotation as Editable;

/**
 * DufCoreNotificationType
 *
 * @ORM\Table(name="duf_core_notification_type")
 * @ORM\Entity(repositoryClass="Duf\CoreBundle\Entity\Repository\DufCoreNotificationTypeRepository")
 */
class DufCoreNotificationType extends DufAdminEntity
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
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     * @Indexable(index_column=true, index_column_name="Enabled", boolean_column=true)
     * @Editable(is_editable=true, label="Enabled", required=true, type="checkbox", order=2,)
     */
    private $enabled;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     * @Editable(is_editable=true, label="Priority", required=false, type="number", order=3, number_type="integer")
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="Duf\AdminBundle\Entity\UserRole")
     * @Editable(is_editable=true, label="Access Level", required=false, type="entity", order=4, relation_index="name", empty_value=true)
     */
    private $accessLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="listener_type", type="string", length=50)
     * @Indexable(index_column=true, index_column_name="Listener Type")
     * @Editable(is_editable=true, label="Listener Type", required=true, type="choice", order=5, choices={"controller":"controller", "doctrine":"doctrine"})
     */
    private $listenerType;

    /**
     * @var string
     *
     * @ORM\Column(name="event_class", type="string", length=255)
     * @Indexable(index_column=true, index_column_name="Event Class")
     * @Editable(is_editable=true, label="Event Class", required=true, type="text", order=6, placeholder="Controller's or Doctrine Entity class")
     */
    private $eventClass;

    /**
     * @var string
     *
     * @ORM\Column(name="event_action", type="string", length=255)
     * @Indexable(index_column=true, index_column_name="Event Action")
     * @Editable(is_editable=true, label="Event Action", required=true, type="text", order=7, placeholder="Controller's action or Doctrine hook")
     */
    private $eventAction;

    /**
     * @var string
     *
     * @ORM\Column(name="target_class", type="string", length=255)
     * @Editable(is_editable=true, label="Target Class", required=true, type="text", order=8, placeholder="Your custom service. Must implement NotificationServiceInterface")
     */
    private $targetClass;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return DufCoreNotificationType
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return DufCoreNotificationType
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
     * Set priority
     *
     * @param integer $priority
     *
     * @return DufCoreNotificationType
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set listenerType
     *
     * @param string $listenerType
     *
     * @return DufCoreNotificationType
     */
    public function setListenerType($listenerType)
    {
        $this->listenerType = $listenerType;

        return $this;
    }

    /**
     * Get listenerType
     *
     * @return string
     */
    public function getListenerType()
    {
        return $this->listenerType;
    }

    /**
     * Set eventClass
     *
     * @param string $eventClass
     *
     * @return DufCoreNotificationType
     */
    public function setEventClass($eventClass)
    {
        $this->eventClass = $eventClass;

        return $this;
    }

    /**
     * Get eventClass
     *
     * @return string
     */
    public function getEventClass()
    {
        return $this->eventClass;
    }

    /**
     * Set eventAction
     *
     * @param string $eventAction
     *
     * @return DufCoreNotificationType
     */
    public function setEventAction($eventAction)
    {
        $this->eventAction = $eventAction;

        return $this;
    }

    /**
     * Get eventAction
     *
     * @return string
     */
    public function getEventAction()
    {
        return $this->eventAction;
    }

    /**
     * Set targetClass
     *
     * @param string $targetClass
     *
     * @return DufCoreNotificationType
     */
    public function setTargetClass($targetClass)
    {
        $this->targetClass = $targetClass;

        return $this;
    }

    /**
     * Get targetClass
     *
     * @return string
     */
    public function getTargetClass()
    {
        return $this->targetClass;
    }

    /**
     * Set accessLevel
     *
     * @param \Duf\AdminBundle\Entity\UserRole $accessLevel
     *
     * @return DufCoreNotificationType
     */
    public function setAccessLevel(\Duf\AdminBundle\Entity\UserRole $accessLevel = null)
    {
        $this->accessLevel = $accessLevel;

        return $this;
    }

    /**
     * Get accessLevel
     *
     * @return \Duf\AdminBundle\Entity\UserRole
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }
}

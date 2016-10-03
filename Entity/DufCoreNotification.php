<?php

namespace Duf\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;

/**
 * DufCoreNotification
 *
 * @ORM\Table(name="duf_core_notification")
 * @ORM\Entity(repositoryClass="Duf\CoreBundle\Entity\Repository\DufCoreNotificationRepository")
 */
class DufCoreNotification extends DufAdminEntity
{
    /**
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $is_read;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_data", type="json_array", nullable=true)
     */
    private $notification_data;

    /**
     * @ORM\ManyToOne(targetEntity="Duf\CoreBundle\Entity\DufCoreNotificationType")
     * @ORM\JoinColumn(nullable=false)
     */
     protected $notification_type;

    /**
     * @ORM\ManyToOne(targetEntity="Duf\AdminBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
     protected $created_by;

    /**
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return DufCoreNotification
     */
    public function setIsRead($isRead)
    {
        $this->is_read = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->is_read;
    }

    /**
     * Set notificationData
     *
     * @param string $notificationData
     *
     * @return DufCoreNotification
     */
    public function setNotificationData($notificationData)
    {
        $this->notification_data = $notificationData;

        return $this;
    }

    /**
     * Get notificationData
     *
     * @return string
     */
    public function getNotificationData()
    {
        return $this->notification_data;
    }

    /**
     * Set notificationType
     *
     * @param \Duf\CoreBundle\Entity\DufCoreNotificationType $notificationType
     *
     * @return DufCoreNotification
     */
    public function setNotificationType(\Duf\CoreBundle\Entity\DufCoreNotificationType $notificationType)
    {
        $this->notification_type = $notificationType;

        return $this;
    }

    /**
     * Get notificationType
     *
     * @return \Duf\CoreBundle\Entity\DufCoreNotificationType
     */
    public function getNotificationType()
    {
        return $this->notification_type;
    }

    /**
     * Set createdBy
     *
     * @param \Duf\AdminBundle\Entity\User $createdBy
     *
     * @return DufCoreNotification
     */
    public function setCreatedBy(\Duf\AdminBundle\Entity\User $createdBy = null)
    {
        $this->created_by = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Duf\AdminBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }
}

<?php

namespace Duf\CoreBundle\Entity\Repository;

/**
 * DufCoreNotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DufCoreNotificationRepository extends \Doctrine\ORM\EntityRepository
{
	public function findByExistingNotification($filters)
	{
        $qb = 	$this->_em->createQueryBuilder()
                          ->select('n')
                          ->from($this->_entityName, 'n')
                          ->where('n.is_read = 0');

        foreach ($filters as $filter_name => $filter_value) {
        	switch ($filter_name) {
        		case 'notification_type':
        			$qb->andWhere('n.notification_type = :notification_type');
        			$qb->setParameter('notification_type', $filter_value);
        			break;
        		case 'notification_data':
        			$qb->andWhere('n.notification_data LIKE :notification_data');
        			$qb->setParameter('notification_data', '%' . json_encode($filter_value) . '%');
        			break;
        	}
        }

        return $qb->getQuery()->getOneOrNullResult();
	}
}

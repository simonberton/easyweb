<?php


namespace App\EasyBundle\Repository;

use App\EasyBundle\Entity\Contact;
use App\EasyBundle\Library\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;

class ContactRepository extends AbstractRepository
{

    public function getEntityClass()
    {
        return Contact::class;
    }

    public function getFilterFields()
    {
        return ['id'];
    }

    public function getNotReadCount()
    {
        $alias = 'c';

        $qb = $this->createQueryBuilder($alias)->select(sprintf(sprintf('count(%s.id)', $alias)));

        $qb->andWhere(sprintf('%s.isRead = :isRead', $alias));
        $qb->setParameter('isRead', false);

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }
}

<?php


namespace App\EasyBundle\Repository;

use App\EasyBundle\Entity\BaseEntity;
use App\EasyBundle\Entity\Post;
use App\EasyBundle\Library\AbstractRepository;

class PostRepository extends AbstractRepository
{
    public function getEntityClass()
    {
        return Post::class;
    }

    public function getFilterFields()
    {
        return ['title'];
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getForHomepage($limit = 6)
    {
        $alias = 'p';

        $qb = $this->createQueryBuilder($alias);

        $qb->andWhere(sprintf('%s.publishStatus = :status', $alias))
            ->andWhere(sprintf('%s.publishSince <= :today OR %s.publishSince IS NULL', $alias, $alias))
            ->andWhere(sprintf('%s.publishUntil >= :today OR %s.publishUntil IS NULL', $alias, $alias))
            ->setParameter('today', date('Y-m-d h:i:s'))
            ->setParameter('status', BaseEntity::STATUS_PUBLISHED);

        $qb->setMaxResults($limit);

        return $qb->getQuery()->execute();
    }
}

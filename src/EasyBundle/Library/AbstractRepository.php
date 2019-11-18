<?php


namespace App\EasyBundle\Library;

use App\EasyBundle\Entity\BaseEntity;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, $this->getEntityClass());
    }

    abstract public function getEntityClass();

    abstract public function getFilterFields();

    /**
     * @param string $filter
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return mixed
     */
    public function getAll(?string $filter, array $orderBy = null, int $limit = null, int $offset = null)
    {
        $alias = 't';

        $qb = $this->createQueryBuilder($alias);

        $this->setFilter($alias, $qb, $filter);

        if ($orderBy && count($orderBy)) {
            foreach ($orderBy as $field => $dir) {
                $qb->orderBy(sprintf('%s.%s', $alias, $field), $dir);
            }
        }

        if (isset($limit) && isset($offset)) {
            $qb->setFirstResult($offset);
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->execute();
    }

    public function getAllCount(?string $filter)
    {
        $alias = 't';

        $qb = $this->createQueryBuilder($alias)->select(sprintf(sprintf('count(%s.id)', $alias)));

        $this->setFilter($alias, $qb, $filter);

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    public function getAvailableByIds(array $ids)
    {
        $alias = 't';

        $qb = $this->createQueryBuilder($alias)
            ->where(sprintf('%s.id IN (:ids)', $alias))
            ->setParameter('ids', $ids);

        return $qb->getQuery()->execute();
    }

    /**
     * @param string $slug
     *
     * @return mixed
     */
    public function getAvailableBySlug(string $slug)
    {
        $alias = 'j';
        $qb = $this->createQueryBuilder($alias);

        $qb->andWhere(sprintf('%s.slug = :slug', $alias))
            ->setParameter('slug', $slug);

        $this->setPublishingRestriction($alias, $qb);

        $qb->setMaxResults(1);

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    protected function setPublishingRestriction(string $alias, QueryBuilder &$qb)
    {
        $qb
            ->andWhere(sprintf('%s.publishStatus = :status', $alias))
            ->andWhere(sprintf('%s.publishSince <= :today OR %s.publishSince IS NULL', $alias, $alias))
            ->andWhere(sprintf('%s.publishUntil >= :today OR %s.publishUntil IS NULL', $alias, $alias))
            ->setParameter('today', date('Y-m-d h:i:s'))
            ->setParameter('status', BaseEntity::STATUS_PUBLISHED);
    }

    /**
     * @param string $alias
     * @param QueryBuilder $qb
     * @param string $filter
     */
    protected function setFilter(string $alias, QueryBuilder $qb, ?string $filter)
    {
        if (!$filter) {
            return;
        }
        foreach ($this->getFilterFields() as $field) {
            $qb->orWhere(sprintf('%s LIKE :%s_filter', sprintf('%s.%s', $alias, $field), $field));
            $qb->setParameter(sprintf('%s_filter', $field), sprintf('%%%s%%', $filter));
        }
    }
}

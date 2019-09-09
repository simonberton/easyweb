<?php


namespace App\EasyBundle\Library;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
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

    /**
     * @param string $filter
     *
     * @return int
     */
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

<?php


namespace App\EasyBundle\Library;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractService
{
    protected $repository;
    protected $logger;
    protected $em;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $this->em->getRepository($this->getEntityClass());
    }

    abstract public function getEntityClass() : string;

    abstract public function getFormClass() : string;

    abstract public function getSortFields() : array;

    public function getEntity()
    {
        try {
            $ref = new \ReflectionClass($this->getEntityClass());
            return $ref->newInstance();
        } catch (\ReflectionException $e) {
            return null;
        }
    }

    /**
     * @param string $filter
     * @param int $page
     * @param int $limit
     * @param string $sort
     * @param string $dir
     *
     * @return array
     * @throws \Exception
     */
    public function getAll(?string $filter, int $page, int $limit, string $sort = '', string $dir = '')
    {
        if ($sort) {
            if (!in_array($sort, $this->getSortFields())) {
                throw new \Exception('Sort by this field is not allowed');
            }
            $orderBy = [(string) $sort => $dir ? strtoupper($dir) : 'ASC'];
        } else {
            $orderBy = [];
        }

        $entities = $this->repository->getAll($filter, $orderBy, $limit, ($page - 1) * $limit);
        $total = $this->repository->getAllCount($filter);

        return ['total' => $total, 'data' => $entities];
    }

    /**
     * @throws \Exception
     */
    public function create($entity)
    {
        if (get_class($entity) !== $this->getEntityClass()) {
            throw new \Exception(sprintf('This service only handles "%s" but "%s" was provided.', $this->getEntityClass(), get_class($entity)));
        }

        try {
            $this->em->persist($entity);
            $this->em->flush();

            $this->logger->info(sprintf('Created %s ID::%s', $this->getEntityClass(), $entity->getId()));

            return $entity;
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Error creating %s. Error: %s', $this->getEntityClass(), $e->getMessage()));

            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function edit($entity)
    {
        if (get_class($entity) !== $this->getEntityClass()) {
            throw new \Exception(sprintf('This service only handles "%s" but "%s" was provided.', $this->getEntityClass(), get_class($entity)));
        }

        try {
            $this->em->flush();

            $this->logger->info(sprintf('Edited %s ID::%s', $this->getEntityClass(), $entity->getId()));

            return $entity;
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Error editing %s ID::%s. Error: %s', $this->getEntityClass(), $entity->getId(), $e->getMessage()));

            throw $e;
        }
    }

    /**
     * @param int $id
     *
     * @return object|null
     */
    public function get(int $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    /**
     * @throws \Exception
     */
    public function delete($entity)
    {
        if (get_class($entity) !== $this->getEntityClass()) {
            throw new \Exception(sprintf('This service only handles "%s" but "%s" was provided.', $this->getEntityClass(), get_class($entity)));
        }

        $id = $entity->getId();
        try {
            $this->em->remove($entity);
            $this->em->flush();

            $this->logger->info(sprintf('Removed %s ID::%s', $this->getEntityClass(), $id));
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Error removing %s ID::%s. Error: %s', $this->getEntityClass(), $id, $e->getMessage()));

            throw $e;
        }
    }
}

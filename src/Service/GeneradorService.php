<?php


namespace App\Service;

use App\Entity\User;
use App\Form\Admin\GeneradorForm;
use App\EasyBundle\Library\AbstractService;
use Doctrine\ORM\EntityManagerInterface;

class GeneradorService extends AbstractService
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(User::class);
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getFormClass(): string
    {
        return GeneradorForm::class;
    }

    public function getSortFields(): array
    {
        return ['email', 'address'];
    }

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
        $users = [];
        foreach ($entities as $user)
        {
            if ($user->hasRole('ROLE_GENERADOR')) {
                $users[] = $user;
            }
        }

        return ['total' => count($users), 'data' => $users];
    }

    public function getListFields(): array
    {
        return [
            ['name' => 'email'],
            ['name' => 'firstName'],
            ['name' => 'lastName'],
            ['name' => 'address'],
        ];
    }
}

    
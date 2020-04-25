<?php


namespace App\Repository;

use App\Entity\Recolector;
use App\EasyBundle\Library\AbstractRepository;

class RecolectorRepository extends AbstractRepository
{

    public function getEntityClass()
    {
        return Recolector::class;
    }

    public function getFilterFields()
    {
        return ['title'];
    }
}
    
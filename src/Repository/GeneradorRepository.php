<?php


namespace App\Repository;

use App\Entity\Generador;
use App\EasyBundle\Library\AbstractRepository;

class GeneradorRepository extends AbstractRepository
{

    public function getEntityClass()
    {
        return Generador::class;
    }

    public function getFilterFields()
    {
        return ['title'];
    }
}
    
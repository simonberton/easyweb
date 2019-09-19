<?php


namespace App\EasyBundle\Repository;


use App\EasyBundle\Entity\Category;
use App\EasyBundle\Library\AbstractRepository;

class CategoryRepository extends AbstractRepository
{

    public function getEntityClass()
    {
        return Category::class;
    }

    public function getFilterFields()
    {
        return ['title'];
    }
}

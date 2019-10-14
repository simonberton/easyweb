<?php


namespace App\EasyBundle\Repository;

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
}

<?php


namespace App\EasyBundle\Repository;

use App\EasyBundle\Entity\AssetImage;
use App\EasyBundle\Library\AbstractRepository;

class AssetImageRepository extends AbstractRepository
{

    public function getEntityClass()
    {
        return AssetImage::class;
    }

    public function getFilterFields()
    {
       return ['id'];
    }
}

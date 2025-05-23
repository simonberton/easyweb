<?php


namespace App\EasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'easy_core_category')]
#[ORM\Entity(repositoryClass: \App\EasyBundle\Repository\CategoryRepository::class)]
class Category extends BaseEntity
{
}

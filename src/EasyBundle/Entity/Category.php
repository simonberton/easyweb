<?php


namespace App\EasyBundle\Entity;

use App\EasyBundle\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'easy_core_category')]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category extends BaseEntity
{
}

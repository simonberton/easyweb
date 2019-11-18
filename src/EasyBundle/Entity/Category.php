<?php


namespace App\EasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\EasyBundle\Repository\CategoryRepository")
 * @ORM\Table(name="easy_core_category")
 */
class Category extends BaseEntity
{
}

<?php


namespace App\EasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\EasyBundle\Repository\PostRepository")
 * @ORM\Table(name="easy_core_post")
 */
class Post extends BaseEntity
{

}

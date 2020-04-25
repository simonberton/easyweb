<?php


namespace App\Entity;
        
use App\EasyBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
        
/**
 * @ORM\Entity(repositoryClass="App\Repository\RecolectorRepository")
 * @ORM\Table(name="recolector")
 */
class Recolector extends User
{

}
    
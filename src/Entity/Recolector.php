<?php


namespace App\Entity;
        
use App\EasyBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
        
#[ORM\Table(name: 'recolector')]
#[ORM\Entity(repositoryClass: \App\Repository\RecolectorRepository::class)]
class Recolector extends User
{

}
    
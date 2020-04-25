<?php


namespace App\Entity;
        
use App\EasyBundle\Entity\BaseEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;
        
/**
 * @ORM\Entity(repositoryClass="App\Repository\GeneradorRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\Table(name="generador")
 */
class Generador extends User
{

}
    
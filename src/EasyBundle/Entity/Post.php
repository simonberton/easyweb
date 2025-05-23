<?php


namespace App\EasyBundle\Entity;

use App\EasyBundle\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'easy_core_post')]
#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post extends BaseEntity
{
    #[ORM\Column(name: 'image_filename', type: 'string', nullable: true)]
    private $mainImageFilename;

    public function getMainImageFilename(): ?string
    {
        return $this->mainImageFilename;
    }

    public function setMainImageFilename(string $mainImageFilename): self
    {
        $this->mainImageFilename = $mainImageFilename;

        return $this;
    }
}

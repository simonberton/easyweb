<?php


namespace App\EasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\EasyBundle\Repository\PostRepository")
 * @ORM\Table(name="easy_core_post")
 */
class Post extends BaseEntity
{
    /**
     * @ORM\Column(name="image_filename", type="string", nullable=true)
     */
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

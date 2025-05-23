<?php


namespace App\EasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Table(name: 'easy_core_asset_image')]
#[ORM\Entity(repositoryClass: \App\EasyBundle\Repository\AssetImageRepository::class)]
class AssetImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'image_id', type: 'integer')]
    private $id;

    #[ORM\Column(name: 'image_filename', type: 'string', length: 128, nullable: false)]
    private $filename;

    #[ORM\Column(name: 'image_mime_type', type: 'string', length: 32, nullable: false)]
    private $mimeType;

    /**
     * @Gedmo\Timestampable(on="create")
     */
    #[ORM\Column(name: 'image_created_at', type: 'datetime', nullable: false)]
    #[Assert\DateTime]
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     */
    #[ORM\Column(name: 'image_modified_at', type: 'datetime', nullable: false)]
    #[Assert\DateTime]
    private $modifiedAt;

    /**
     * @Gedmo\Blameable(on="create")
     */
    #[ORM\Column(name: 'image_created_by', type: 'string', length: 128, nullable: true)]
    #[Assert\Length(max: 128)]
    private $createdBy;

    public function __toString() : string
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}

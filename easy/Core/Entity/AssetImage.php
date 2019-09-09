<?php


namespace Easy\Core\Entity;

/**
 * @ORM\Entity(repositoryClass="Easy\Core\Repository\AssetImageRepository")
 * @ORM\Table(name="easy_core_asset_image")
 */
class AssetImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="image_id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="image_filename", type="string", length=128, nullable=false)
     */
    private $filename;

    /**
     * @ORM\Column(name="image_mime_type", type="string", length=32, nullable=false)
     */
    private $mimeType;

    /**
     * @Assert\DateTime()
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="image_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @Assert\DateTime()
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="image_modified_at", type="datetime", nullable=false)
     */
    private $modifiedAt;

    /**
     * @Assert\Length(max=128)
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="image_created_by", type="string", length=128, nullable=true)
     */
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

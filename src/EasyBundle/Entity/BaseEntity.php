<?php


namespace App\EasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\MappedSuperclass]
abstract class BaseEntity
{
    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';
    const STATUS_DRAFT = 'draft';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private $id;

    #[ORM\Column(name: 'title', type: 'string', length: 128, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    private $title;

    #[ORM\Column(name: 'slug', type: 'string', length: 128, nullable: false)]
    #[Assert\Length(max: 128)]
    private $slug;

    #[Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private \DateTimeInterface $createdAt;

    #[Timestampable(on: 'update')]
    #[ORM\Column(name: 'modified_at', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $modifiedAt = null;

    #[ORM\Column(name: 'description', type: 'string', length: 256, nullable: true)]
    #[Assert\Length(max: 256)]
    private $description;

    #[ORM\Column(name: 'content', type: 'text', nullable: true)]
    private $content;

    #[ORM\Column(name: 'publish_since', type: 'text', nullable: true)]
    private $publishSince;

    #[ORM\Column(name: 'publish_until', type: 'text', nullable: true)]
    private $publishUntil;

    #[ORM\Column(name: 'publish_status', type: 'string', length: 32, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 32)]
    private $publishStatus;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishStatus(): ?string
    {
        return $this->publishStatus;
    }

    public function setPublishStatus(?string $publishStatus): self
    {
        $this->publishStatus = $publishStatus;

        return $this;
    }

    public function getPublishSince(): ?string
    {
        return $this->publishSince;
    }

    public function setPublishSince(?string $publishSince): self
    {
        $this->publishSince = $publishSince;

        return $this;
    }

    public function getPublishUntil(): ?string
    {
        return $this->publishUntil;
    }

    public function setPublishUntil(?string $publishUntil): self
    {
        $this->publishUntil = $publishUntil;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): \DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }
}

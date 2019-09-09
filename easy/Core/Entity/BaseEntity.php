<?php


namespace Easy\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Easy\Core\Entity\AssetImage;
use Easy\Core\Entity\Category;

/**
 * @ORM\MappedSuperclass
 */
abstract class BaseEntity
{
    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';
    const STATUS_DRAFT = 'draft';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=128)
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @Assert\Length(max=128)
     *
     * @ORM\Column(name="slug", type="string", length=128, nullable=false)
     * @Gedmo\Slug(updatable=false, unique=true, unique_base="domain", fields={"title"})
     */
    private $slug;

    /**
     * @Assert\Length(max=256)
     * @ORM\Column(name="description", type="string", length=256, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Easy\Core\Entity\AssetImage")
     * @ORM\JoinColumn(name="main_image", referencedColumnName="image_id", nullable=true)
     */
    private $mainImage;

    /**
     * @Assert\Type(type="Easy\Core\Entity\Category")
     * @Assert\NotNull()
     *
     * @ORM\ManyToOne(targetEntity="Easy\Core\Entity\Category")
     * @ORM\JoinColumn(name="category", referencedColumnName="category_id", nullable=false)
     */
    private $category;

    /**
     * @Assert\DateTime()
     *
     * @ORM\Column(name="publish_since", type="datetime", nullable=true)
     */
    private $publishSince;

    /**
     * @Assert\DateTime()
     *
     * @ORM\Column(name="publish_until", type="datetime", nullable=true)
     */
    private $publishUntil;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     *
     * @ORM\Column(name="publish_status", type="string", length=32, nullable=false)
     */
    private $publishStatus;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
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

    public function getMainImage(): ?AssetImage
    {
        return $this->mainImage;
    }

    public function setMainImage(?AssetImage $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    public function getCategory(?Category $category): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

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

    public function getPublishSince(): ?\DateTimeInterface
    {
        return $this->publishSince;
    }

    public function setPublishSince(?\DateTimeInterface $publishSince): self
    {
        $this->publishSince = $publishSince;

        return $this;
    }

    public function getPublishUntil(): ?\DateTimeInterface
    {
        return $this->publishUntil;
    }

    public function setPublishUntil(?\DateTimeInterface $publishUntil): self
    {
        $this->publishUntil = $publishUntil;

        return $this;
    }
}

<?php


namespace App\EasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\EasyBundle\Repository\CategoryRepository")
 * @ORM\Table(name="easy_core_category")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="category_id", type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=128)
     * @ORM\Column(name="category_title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @Assert\Length(max=1256)
     * @ORM\Column(name="category_description", type="string", length=256, nullable=true)
     */
    private $description;

    /**
     * @Assert\Length(max=128)
     *
     * @ORM\Column(name="category_slug", type="string", length=128, nullable=false)
     * @Gedmo\Slug(updatable=false, unique=true, unique_base="domain", fields={"title"})
     */
    private $slug;

    public function __toString()
    {
        return (string) $this->title;
    }

    public function getId(): ?int
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

}

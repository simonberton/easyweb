<?php


namespace App\EasyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\EasyBundle\Repository\ContactRepository")
 * @ORM\Table(name="easy_core_contact")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="contact_id", type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=128)
     * @ORM\Column(name="contact_name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=128)
     * @Assert\Email()
     * @ORM\Column(name="contact_email", type="string", length=128, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(name="contact_phone", type="string", length=32, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(name="contact_message", type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(name="contact_accepts_notifications", type="boolean", nullable=false)
     */
    private $acceptsNotifications;

    /**
     * @ORM\Column(name="contact_extra_data", type="text", nullable=true)
     */
    private $extraData;

    /**
     * @ORM\Column(name="contact_is_read", type="boolean", nullable=true)
     */
    private $isRead = false;

    /**
     * @Assert\DateTime()
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="contact_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getExtraData() : ?string
    {
        return $this->extraData;
    }

    public function setExtraData(?string $extraData) : Contact
    {
        $this->extraData = $extraData;

        return $this;
    }

    public function getAcceptsNotifications(): ?bool
    {
        return $this->acceptsNotifications;
    }

    public function setAcceptsNotifications(bool $acceptsNotifications)
    {
        $this->acceptsNotifications = $acceptsNotifications;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt->format('m/d/Y');
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead($isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

}

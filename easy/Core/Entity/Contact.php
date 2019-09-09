<?php


namespace Easy\Core\Entity;

/**
 * @ORM\Entity(repositoryClass="Easy\Core\Repository\ContactRepository")
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
     * @ORM\Column(name="contact_extra_data", type="json_array", nullable=true)
     */
    private $extraData;

    /**
     * @Assert\DateTime()
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="contact_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @Assert\DateTime()
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="contact_modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

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

    public function getExtraData() : ?array
    {
        return $this->extraData;
    }

    public function setExtraData(?array $extraData) : Contact
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

}

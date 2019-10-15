<?php


namespace App\EasyBundle\Service;

use App\EasyBundle\Entity\Contact;
use App\EasyBundle\Form\Admin\ContactForm;
use App\EasyBundle\Library\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ContactService extends AbstractService
{

    protected $repository;
    protected $logger;
    protected $em;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->repository = $this->em->getRepository($this->getEntityClass());

        parent::__construct($logger, $em);
    }

    public function getEntityClass(): string
    {
        return Contact::class;
    }

    public function getFormClass(): string
    {
        return ContactForm::class;
    }

    public function getSortFields(): array
    {
        return ['name', 'email'];
    }

    public function getListFields(): array
    {
        return [
            ['name' => 'name'],
            ['name' => 'email'],
            ['name' => 'createdAt'],
        ];
    }

    public function getNotReadCount()
    {
        return $this->repository->getNotReadCount();
    }
}

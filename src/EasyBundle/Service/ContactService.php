<?php


namespace App\EasyBundle\Service;

use App\EasyBundle\Entity\Contact;
use App\EasyBundle\Form\Admin\ContactForm;
use App\EasyBundle\Library\AbstractService;

class ContactService extends AbstractService
{

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
            ['name' => 'publishStatus'],
        ];
    }
}

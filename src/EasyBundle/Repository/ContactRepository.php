<?php


namespace App\EasyBundle\Repository;

use App\EasyBundle\Entity\Contact;
use App\EasyBundle\Library\AbstractRepository;

class ContactRepository extends AbstractRepository
{

    public function getEntityClass()
    {
        return Contact::class;
    }

    public function getFilterFields()
    {
        return ['id'];
    }
}

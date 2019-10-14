<?php


namespace App\EasyBundle\Service;

use App\EasyBundle\Entity\Post;
use App\EasyBundle\Form\Admin\PostForm;
use App\EasyBundle\Library\AbstractService;

class PostService extends AbstractService
{

    public function getEntityClass(): string
    {
        return Post::class;
    }

    public function getFormClass(): string
    {
        return PostForm::class;
    }

    public function getSortFields(): array
    {
        return ['title', 'publishStatus'];
    }

    public function getListFields(): array
    {
        return [
            ['name' => 'title'],
            ['name' => 'slug'],
            ['name' => 'publishStatus'],
        ];
    }
}

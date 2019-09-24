<?php


namespace App\EasyBundle\Service;


use App\EasyBundle\Entity\Category;
use App\EasyBundle\Form\Admin\CategoryForm;
use App\EasyBundle\Library\AbstractService;

class CategoryService extends AbstractService
{

    public function getEntityClass(): string
    {
        return Category::class;
    }

    public function getFormClass(): string
    {
        return CategoryForm::class;
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

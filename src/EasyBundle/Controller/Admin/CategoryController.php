<?php


namespace App\EasyBundle\Controller\Admin;


use App\EasyBundle\Library\AbstractAdminController;
use App\EasyBundle\Library\AbstractService;
use App\EasyBundle\Service\CategoryService;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="admin_category_")
 */
class CategoryController extends AbstractAdminController
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;

       // parent::__construct($translator, $imageService);
    }

    protected function getService(): AbstractService
    {
        return $this->service;
    }

    protected function getListFields(): array
    {
        return $this->getService()->getListFields();
    }

    protected function getRoutePrefix(): string
    {
        return 'admin_category';
    }
}

<?php


namespace App\EasyBundle\Controller\Admin;

use App\EasyBundle\Library\AbstractAdminController;
use App\EasyBundle\Library\AbstractService;
use App\EasyBundle\Service\PostService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/post", name="admin_post_")
 */
class PostController extends AbstractAdminController
{
    protected $service;

    public function __construct(PostService $categoryService, TranslatorInterface $translator)
    {
        $this->service = $categoryService;

        parent::__construct($translator);
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
        return 'admin_post';
    }
}

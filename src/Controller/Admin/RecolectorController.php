<?php


namespace App\Controller\Admin;

use App\EasyBundle\Library\AbstractAdminController;
use App\EasyBundle\Library\AbstractService;
use App\Service\RecolectorService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/admin/recolector", name="admin_recolector_")
 */
class RecolectorController extends AbstractAdminController
{
    protected $service;

    public function __construct(RecolectorService $service, TranslatorInterface $translator)
    {
        $this->service = $service;

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
        return 'admin_recolector';
    }
}


    
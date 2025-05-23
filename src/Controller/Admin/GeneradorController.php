<?php


namespace App\Controller\Admin;

use App\EasyBundle\Library\AbstractAdminController;
use App\EasyBundle\Library\AbstractService;
use App\Service\GeneradorService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/admin/generador', name: 'admin_generador_')]
class GeneradorController extends AbstractAdminController
{
    protected $service;

    public function __construct(GeneradorService $service, TranslatorInterface $translator)
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
        return 'admin_generador';
    }
}


    
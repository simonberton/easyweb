<?php

namespace App\EasyBundle\Twig;

use App\EasyBundle\Service\ContactService;
use App\EasyBundle\Service\SidebarService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminExtension extends AbstractExtension
{
    protected $sidebarService;
    protected $contactService;

    public function __construct(SidebarService $sidebarService, ContactService $contactService)
    {
        $this->sidebarService = $sidebarService;
        $this->contactService = $contactService;

    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_sidebar', [$this, 'getSidebar'], ['is_safe' => ['html']]),
            new TwigFunction('contact_count', [$this, 'getContactCount'], ['is_safe' => ['html']]),
        ];
    }

    public function getSidebar()
    {
        return $this->sidebarService->getSidebar();
    }

    public function getContactCount()
    {
        $f = '';

        return $this->contactService->getAllCount($f);
    }
}

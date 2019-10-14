<?php

namespace App\EasyBundle\Twig;

use App\EasyBundle\Service\SidebarService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarExtension extends AbstractExtension
{
    protected $sidebarService;

    public function __construct(SidebarService $sidebarService)
    {
        $this->sidebarService = $sidebarService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_sidebar', [$this, 'getSidebar'], ['is_safe' => ['html']]),
        ];
    }

    public function getSidebar()
    {
        return $this->sidebarService->getSidebar();
    }
}

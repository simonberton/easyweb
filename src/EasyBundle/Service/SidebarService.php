<?php


namespace App\EasyBundle\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SidebarService
{
    private $router;
    private $twig;
    private $translator;

    public function __construct(UrlGeneratorInterface $router, Environment $twig, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->translator = $translator;
    }

    public function getSidebar()
    {
        $sidebar = [];

        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();

        foreach ($allRoutes as $key => $route) {
            if ($key !== 'admin_index') {
                if (strpos($key, 'admin_') !== false &&
                    (strpos($key, '_create') !== false || strpos($key, '_index') !== false)) {
                    $arrayKey = str_replace('admin_', '', $key);
                    $arrayKey = str_replace('_create', '', $arrayKey);
                    $arrayKey = str_replace('_index', '', $arrayKey);

                    if (!isset($sidebar[$arrayKey]) && $arrayKey !== 'contact') {
                        $sidebar[$arrayKey][] = [
                            'name' => sprintf('%s',
                                $this->translator->trans('crud.create', [], 'easy_cms')
                            ),
                            'icon' => $this->getIcon(),
                            'route' => $this->router->generate(sprintf('admin_%s_create', $arrayKey))
                        ];
                        $sidebar[$arrayKey][] = [
                            'name' => sprintf('%s',
                                $this->translator->trans('crud.list', [], 'easy_cms')
                            ),
                            'icon' => $this->getIcon(false),
                            'route' => $this->router->generate(sprintf('admin_%s_index', $arrayKey)),
                        ];
                    }
                }
            }
        }

        return $this->twig->render('@Easy/cms/menu.html.twig', [
            'sidebar' => $sidebar
        ]);

        return $sidebar;
    }

    private function getIcon($create = true)
    {
        if ($create) {
            return 'fa-plus-square';
        }
        return 'fa-list-alt';
    }
}

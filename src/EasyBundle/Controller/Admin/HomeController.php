<?php

namespace App\EasyBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    #[Route(path: '/admin/', name: 'admin_index')]
    public function index()
    {
        return $this->render('@Easy/cms/home.html.twig');
    }
}

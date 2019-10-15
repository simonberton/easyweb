<?php


namespace App\EasyBundle\Controller\Admin;

use App\EasyBundle\Service\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/contact", name="admin_contact_")
 *
 * @param Request $request
 *
 * @return Response
 * @throws \Exception
 */
class ContactController
{
    protected $service;

    public function __construct(ContactService $contactService, TranslatorInterface $translator)
    {
        $this->service = $contactService;

        parent::__construct($translator);
    }

    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        return $this->render('@Easy/cms/home.html.twig');
    }

    /**
     * @Route("/{id}", name="show")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function show(Request $request, int $id)
    {

    }
}

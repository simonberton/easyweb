<?php


namespace App\EasyBundle\Controller\Admin;

use App\EasyBundle\Service\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/contact", name="admin_contact_")
 *
 * @param Request $request
 *
 * @return Response
 * @throws \Exception
 */
class ContactController extends AbstractController
{
    protected $service;
    protected $translator;

    public function __construct(ContactService $contactService, TranslatorInterface $translator)
    {
        $this->service = $contactService;
        $this->translator = $translator;
    }

    protected function getLimit(): int
    {
        return 20;
    }

    protected function getTranslatorPrefix() : string
    {
        return 'easy_cms';
    }

    protected function getRoutePrefix(): string
    {
        return 'admin_contact';
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
        $page = (int)$request->get('page', 1);
        if ($page < 1) {
            $page = 1;
        }

        $limit = (int)$request->get('limit', $this->getLimit());
        if (!$limit) {
            $limit = $this->getLimit();
        }

        $filter = (string)$request->get('f');

        $data = $this->service->getAll($filter, $page, $limit, (string)$request->get('sort'), (string)$request->get('dir'));

        $paginationData = [
            'currentPage' => $page,
            'url' => $request->get('_route'),
            'nbPages' => ceil($data['total'] / $limit),
            'currentCount' => count($data['data']),
            'totalCount' => $data['total'],
            'limit' => $limit
        ];

        // Mark sortable fields
        $listFields = $this->service->getListFields();
        foreach ($listFields as &$field) {
            $field['sortable'] = false;
            if (in_array($field['name'], $this->service->getSortFields())) {
                $field['sortable'] = true;
            }
        }
        return $this->render(
            '@Easy/cms/crud/index.html.twig',
            array_merge(
                $data,
                [
                    'sort' => $request->query->get('sort'),
                    'dir' => $request->query->get('dir'),
                    'paginationData' => $paginationData,
                    'params' => $request->query->all(),
                    'filter' => $filter,
                    'trans_prefix' => $this->getTranslatorPrefix(),
                    'route_prefix' => $this->getRoutePrefix(),
                    'list_fields' => $listFields,
                ]
            )
        );
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
        return $this->render('@Easy/cms/home.html.twig');
    }
}

<?php


namespace App\EasyBundle\Controller\Admin;

use App\EasyBundle\Service\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 * @param Request $request
 *
 * @return Response
 * @throws \Exception
 */
#[Route(path: '/admin/contact', name: 'admin_contact_')]
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
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    #[Route(path: '/', name: 'index')]
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
            '@Easy/cms/contact/index.html.twig',
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
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     */
    #[Route(path: '/edit/{id}', name: 'edit')]
    public function edit(Request $request, int $id): Response
    {
        $entity = $this->service->get($id);
        if ($entity === null || get_class($entity) !== $this->service->getEntityClass()) {
            throw new NotFoundHttpException(sprintf($this->translator->trans('not_found', [], $this->getTranslatorPrefix()), $id));
        }

        $entity->setIsRead(true);
        $this->service->edit($entity);

        $form = $this->createForm(
            $this->service->getFormClass(),
            $entity,
            [
                'translation_domain' => $this->getTranslatorPrefix()
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->service->edit($entity);

                    $this->addFlash('cms_success', $this->translator->trans('edit_success', [], $this->getTranslatorPrefix()));

                    return $this->redirectToRoute($this->getRoutePrefix() . '_index');
                } catch (\Exception $e) {
                    $this->addFlash('cms_error', $this->translator->trans('edit_error', [], $this->getTranslatorPrefix()));
                }
            } else {
                $this->addFlash('cms_error', $this->getFormErrorMessagesList($form));
            }
        }

        return $this->render(
            '@Easy/cms/crud/show.html.twig',
            [
                'form' => $form->createView(),
                'isCreate' => false,
                'trans_prefix' => $this->getTranslatorPrefix(),
                'route_prefix' => $this->getRoutePrefix(),
            ]
        );
    }

    /**
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    #[Route(path: '/delete/{id}', name: 'delete', methods: 'POST')]
    public function delete(Request $request, int $id): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->json(
                ['msg' => $this->translator->trans('bad_request', [], 'easy_cms')],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $entity = $this->service->get($id);
            if ($entity === null || get_class($entity) !== $this->service->getEntityClass()) {
                return $this->json([
                    'msg' => sprintf($this->translator->trans('not_found', [], $this->getTranslatorPrefix()), $id)
                ], Response::HTTP_NOT_FOUND);
            }

            $this->service->delete($entity);

            return $this->json([
                'id' => $id,
                'title' => $this->translator->trans('delete_success_title', [], 'easy_cms'),
                'msg' => $this->translator->trans('delete_success_message', [], $this->getTranslatorPrefix()),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'msg' => $this->translator->trans('delete_failed', [], 'easy_cms')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

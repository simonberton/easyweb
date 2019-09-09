<?php


namespace App\EasyBundle\Library;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

abstract class AbstractController extends BaseController
{
    abstract protected function getService() : AbstractService;

    abstract protected function getListFields() : array;

    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request) : Response
    {
        $this->denyAccessUnlessAllowed('view');

        $page = (int) $request->get('page', 1);
        if ($page < 1) {
            $page = 1;
        }

        $limit = (int) $request->get('limit', $this->getLimit());
        if (!$limit) {
            $limit = $this->getLimit();
        }

        $filter = (string) $request->get('f');

        $data = $this->getService()->getAll($filter, $page, $limit, (string)$request->get('sort'), (string)$request->get('dir'));

        $paginationData = [
            'currentPage' => $page,
            'url' => $request->get('_route'),
            'nbPages' => ceil($data['total']/$limit),
            'currentCount' => count($data['data']),
            'totalCount' => $data['total'],
            'limit' => $limit
        ];

        // Mark sortable fields
        $listFields = $this->getListFields();
        foreach ($listFields as &$field) {
            $field['sortable'] = false;
            if (in_array($field['name'], $this->getService()->getSortFields())) {
                $field['sortable'] = true;
            }
        }

        $extraData = [];
        if (isset($this->events[self::EVENT_INDEX_EXTRA_DATA])) {
            $extraData = $this->events[self::EVENT_INDEX_EXTRA_DATA]();
        }

        return $this->render(
            $this->getTemplate('index.html.twig'),
            array_merge(
                $data,
                [
                    'sort' => $request->query->get('sort'),
                    'dir' => $request->query->get('dir'),
                    'paginationData' => $paginationData,
                    'params' => $request->query->all(),
                    'filter' => $filter,
                    'trans_prefix' => $this->getTranslatorPrefix(),
                    'route_prefix' => $this->getRouteNamePrefix(),
                    'list_fields' => $listFields,
                ],
                $extraData
            )
        );
    }

    /**
     * @Route("/create", name="create")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request) : Response
    {
        $this->denyAccessUnlessAllowed('create');

        $entity = $this->getService()->getEntity();
        if ($entity === null) {
            throw new BadRequestHttpException($this->translator->trans('bad_request', [], 'ws_cms'));
        }

        if (isset($this->events[self::EVENT_CREATE_NEW_ENTITY])) {
            $this->events[self::EVENT_CREATE_NEW_ENTITY]($entity);
        }

        if (isset($this->events[self::EVENT_CREATE_CREATE_FORM])) {
            $form = $this->events[self::EVENT_CREATE_CREATE_FORM]($entity);
        } else {
            $form = $this->createForm(
                $this->getService()->getFormClass(),
                $entity,
                [
                    'translation_domain' => $this->getTranslatorPrefix()
                ]
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->getService()->create($entity);

                    $this->handleImages($form, $entity);

                    $this->addFlash('cms_success', $this->trans('create_success', [], $this->getTranslatorPrefix()));

                    return $this->redirect($this->generateUrl($this->getRouteNamePrefix() . '_index'));
                } catch (\Exception $e) {
                    $this->addFlash('cms_error', $this->trans('create_error', [], $this->getTranslatorPrefix()));
                }
            } else {
                $this->addFlash('cms_error', $this->getFormErrorMessagesList($form));
            }
        }

        $extraData = [];
        if (isset($this->events[self::EVENT_CREATE_EXTRA_DATA])) {
            $extraData = $this->events[self::EVENT_CREATE_EXTRA_DATA]();
        }

        return $this->render($this->getTemplate('show.html.twig'), array_merge([
            'form' => $form->createView(),
            'isCreate' => true,
            'trans_prefix' => $this->getTranslatorPrefix(),
            'route_prefix' => $this->getRouteNamePrefix(),
        ], $extraData));
    }

    /**
     * @Route ("/edit/{id}", name="edit")
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, int $id) : Response
    {
        $this->denyAccessUnlessAllowed('edit');

        $entity = $this->getService()->get($id);
        if ($entity === null || get_class($entity) !== $this->getService()->getEntityClass()) {
            throw new NotFoundHttpException(sprintf($this->trans('not_found', [], $this->getTranslatorPrefix()), $id));
        }

        if (isset($this->events[self::EVENT_EDIT_CREATE_FORM])) {
            $form = $this->events[self::EVENT_EDIT_CREATE_FORM]($entity);
        } else {
            $form = $this->createForm(
                $this->getService()->getFormClass(),
                $entity,
                [
                    'translation_domain' => $this->getTranslatorPrefix()
                ]
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->getService()->edit($entity);

                    $this->handleImages($form, $entity);

                    $this->addFlash('cms_success', $this->trans('edit_success', [], $this->getTranslatorPrefix()));

                    return $this->redirect($this->generateUrl($this->getRouteNamePrefix() . '_index'));
                } catch (\Exception $e) {
                    $this->addFlash('cms_error', $this->trans('edit_error', [], $this->getTranslatorPrefix()));
                }
            } else {
                $this->addFlash('cms_error', $this->getFormErrorMessagesList($form));
            }
        }

        $extraData = [];
        if (isset($this->events[self::EVENT_EDIT_EXTRA_DATA])) {
            $extraData = $this->events[self::EVENT_EDIT_EXTRA_DATA]();
        }

        return $this->render($this->getTemplate('show.html.twig'), array_merge([
            'form' => $form->createView(),
            'isCreate' => false,
            'trans_prefix' => $this->getTranslatorPrefix(),
            'route_prefix' => $this->getRouteNamePrefix(),
        ], $extraData));
    }

    /**
     * @Route ("/delete/{id}", name="delete", methods="POST"))
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function delete(Request $request, int $id) : Response
    {
        try {
            $this->denyAccessUnlessAllowed('delete');
        } catch (AccessDeniedException $exception) {
            return $this->json(['msg' => $this->trans('not_allowed', [], 'ws_cms')], Response::HTTP_FORBIDDEN);
        }

        if (!$request->isXmlHttpRequest()) {
            return $this->json(
                ['msg' => $this->translator->trans('bad_request', [], 'ws_cms')],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $entity = $this->getService()->get($id);
            if ($entity === null || get_class($entity) !== $this->getService()->getEntityClass()) {
                return $this->json([
                    'msg' => sprintf($this->trans('not_found', [], $this->getTranslatorPrefix()), $id)
                ], Response::HTTP_NOT_FOUND);
            }

            $this->getService()->delete($entity);

            return $this->json([
                'id' => $id,
                'title' => $this->trans('delete_title_success', [], 'ws_cms'),
                'msg' => $this->trans('delete_success', [], $this->getTranslatorPrefix()),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'msg' => $this->trans('delete_failed', [], 'ws_cms')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

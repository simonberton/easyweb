<?php


namespace App\EasyBundle\Library;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;

/**
 * @Route("/admin", name="easy_admin_")
 *
 * @param Request $request
 *
 * @return Response
 * @throws \Exception
 */
abstract class AbstractAdminController extends BaseController
{
    const EVENT_CREATE_NEW_ENTITY = 'create.new_entity';
    const EVENT_CREATE_CREATE_FORM = 'create.create_form';
    const EVENT_EDIT_CREATE_FORM = 'edit.create_form';

    protected $events = [];
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    abstract protected function getService(): AbstractService;

    abstract protected function getListFields(): array;

    abstract protected function getRoutePrefix(): string;

    protected function getLimit(): int
    {
        return 20;
    }

    protected function addEvent($event, \Closure $callback)
    {
        $this->events[$event] = $callback;
    }

    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request): Response
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

        $data = $this->getService()->getAll($filter, $page, $limit, (string)$request->get('sort'), (string)$request->get('dir'));

        $paginationData = [
            'currentPage' => $page,
            'url' => $request->get('_route'),
            'nbPages' => ceil($data['total'] / $limit),
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
                    'route_prefix' => $this->getRoutePrefix(),
                    'list_fields' => $listFields,
                ]
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
    public function create(Request $request): Response
    {
        $entity = $this->getService()->getEntity();
        if ($entity === null) {
            throw new BadRequestHttpException($this->translator->trans('bad_request', [], 'cms'));
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

                    $this->addFlash('cms_success', $this->trans('create_success', [], $this->getTranslatorPrefix()));

                    return $this->redirect($this->generateUrl($this->getRoutePrefix() . '_index'));
                } catch (\Exception $e) {
                    $this->addFlash('cms_error', $this->trans('create_error', [], $this->getTranslatorPrefix()));
                }
            } else {
                $this->addFlash('cms_error', $this->getFormErrorMessagesList($form));
            }
        }


        return $this->render($this->getTemplate('show.html.twig'), [
            'form' => $form->createView(),
            'isCreate' => true,
            'trans_prefix' => $this->getTranslatorPrefix(),
            'route_prefix' => $this->getRoutePrefix(),
        ]);
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
    public function edit(Request $request, int $id): Response
    {
        //$this->denyAccessUnlessAllowed('edit');

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

                    $this->addFlash('cms_success', $this->trans('edit_success', [], $this->getTranslatorPrefix()));

                    return $this->redirect($this->generateUrl($this->getRoutePrefix() . '_index'));
                } catch (\Exception $e) {
                    $this->addFlash('cms_error', $this->trans('edit_error', [], $this->getTranslatorPrefix()));
                }
            } else {
                $this->addFlash('cms_error', $this->getFormErrorMessagesList($form));
            }
        }

        return $this->render($this->getTemplate('show.html.twig'),
            [
                'form' => $form->createView(),
                'isCreate' => false,
                'trans_prefix' => $this->getTranslatorPrefix(),
                'route_prefix' => $this->getRoutePrefix(),
            ]
        );
    }

    /**
     * @Route ("/delete/{id}", name="delete", methods="POST"))
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->json(
                ['msg' => $this->trans('bad_request', [], 'easy_cms')],
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
                'title' => $this->trans('delete_success_title', [], 'easy_cms'),
                'msg' => $this->trans('delete_success_message', [], $this->getTranslatorPrefix()),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'msg' => $this->trans('delete_failed', [], 'easy_cms')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function getTemplate($template, $entity = null): string
    {
        if ($this->useCRUDTemplate($template)) {
            return sprintf('@Easy/cms/crud/%s', $template);
        }

        $routePrefix = '';
        $controllerClass = get_class($this);
        $classPath = explode('\\', $controllerClass);

        if ($classPath[0] === 'EASY') {
            $controllerName = strtolower(str_replace('Controller', '', $classPath[4]));
            $routePrefix = sprintf('@%s%s/%s/%s', $classPath[0], $classPath[1], strtolower($classPath[3]), $controllerName);
        }

        return $routePrefix;
    }

    protected function useCRUDTemplate($template) : bool
    {
        if ($template == 'index.html.twig') {
            return true;
        }

        if ($template == 'show.html.twig') {
            return true;
        }

        return false;
    }

    protected function getTranslatorPrefix() : string
    {
        return 'easy_cms';
    }

    protected function getFormErrorMessagesList(Form $form, int $output = 0)
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        if ($output == 0) {
            return implode(PHP_EOL, $errors);
        }

        return $errors;
    }
}

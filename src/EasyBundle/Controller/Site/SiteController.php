<?php


namespace App\EasyBundle\Controller\Site;

use App\EasyBundle\Entity\Contact;
use App\EasyBundle\Entity\Post;
use App\EasyBundle\Form\Site\ContactForm;
use App\EasyBundle\Message\SendContactEmail;
use App\EasyBundle\Repository\PostRepository;
use App\EasyBundle\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use App\EasyBundle\Service\HomepageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/', name: 'site_')]
class SiteController extends BaseController
{
    /** @var PostRepository  */
    private $postRepository;

    /** @var TranslatorInterface */
    private $translator;

    /** @var ContactService */
    private $contactService;

    public function __construct(
        TranslatorInterface $translator,
        PostRepository $postRepository,
        ContactService $contactService
    ) {
        $this->postRepository = $postRepository;
        $this->translator = $translator;
        $this->contactService = $contactService;
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    #[Route(path: '/', name: 'homepage')]
    public function index(Request $request)
    {
        $posts = $this->postRepository->getForHomepage($this->getParameter('site_homepage_posts_limit'));

        return $this->render('@Easy/site/index.html.twig', [
            'show_posts' => $this->getParameter('site_show_posts', false),
            'posts' => $posts,
            'title' => $this->translator->trans('site.title', [], 'easy_cms')
        ]);
    }

    /**
     *
     * @param $slug
     *
     * @return Response
     * @throws \Exception
     */
    #[Route(path: '/post/{slug}', name: 'post_detail', methods: ['GET'])]
    public function postDetail($slug)
    {
        $post = $this->postRepository->getAvailableBySlug($slug);

        if (!$post instanceof  Post) {
            throw new NotFoundHttpException($this->translator->trans('site.post_not_found', [], 'easy_cms'));
        }

        return $this->render('@Easy/site/detail.html.twig', [
            'post' => $post,
            'title' => $post->getTitle()
        ]);
    }

    /**
     *
     * @param Request $request
     *
     * @param MessageBusInterface $messageBus
     * @return Response|JsonResponse
     */
    #[Route(path: '/contact-us', name: 'contact', methods: ['POST', 'GET'])]
    public function contact(Request $request, MessageBusInterface $messageBus)
    {
        $contactResult = null;

        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->contactService->create($contact);

                    $sendContactEmail = new SendContactEmail('Contact email bodyHtml');
                    $messageBus->dispatch($sendContactEmail);

                    return new JsonResponse([
                        'result' => 'success'
                    ]);
                } catch (\Exception $e) {
                    $contactResult = 'error';
                }
            } else {
                $contactResult = 'error';
            }
        }

        return $this->render('@Easy/site/contact.html.twig', [
            'form' => $form->createView(),
            'error' => $contactResult
        ]);
    }
}

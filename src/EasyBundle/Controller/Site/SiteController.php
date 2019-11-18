<?php


namespace App\EasyBundle\Controller\Site;

use App\EasyBundle\Entity\Post;
use App\EasyBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use App\EasyBundle\Service\HomepageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/", name="site_")
 */
class SiteController extends BaseController
{
    /** @var PostRepository  */
    private $postRepository;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(TranslatorInterface $translator, PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
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
     * @Route("/{slug}", name="post_detail")
     *
     * @param $slug
     *
     * @return Response
     * @throws \Exception
     */
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
}

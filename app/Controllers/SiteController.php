<?php


namespace App\Controllers;


use App\Entities\Post;
use Framework\controller\AbstractController;
use Framework\http\RedirectResponse;
use Framework\http\Response;
use App\Services\PostService;
use Framework\Session\SessionInterface;

class SiteController extends AbstractController
{
    public function __construct(private PostService $service, private SessionInterface $session)
    {
    }

    public function index(): Response
    {
        return $this->render('index.html.twig', ['name' => 'oleg']);
    }

    public function view(int $id): Response
    {
        $post = $this->service->findOrFail($id);
        return $this->render('view.html.twig', ['post' => $post, 'session' =>$this->session]);
    }

    public function create(): Response
    {
        return $this->render('create_post.html.twig');
    }

    public function store(): Response
    {
        $post = Post::create($this->request->postData['title'], $this->request->postData['body']);
        $post = $this->service->save($post);
        $this->request->getSession()->setFlash('success','Post successful created');
        return new RedirectResponse("/posts/{$post->getId()}");
    }
}
<?php


class PostsController extends Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->loadModel('posts');
	}
	
	public function index_action()
	{
	    $this->view->posts = $this->postsModel->last(3, true);

	    foreach ($this->view->posts as $post)
        {
            $post->truncateText(600);
        }

		$this->view->render('posts/index');
	}

	public function show_action($slug)
    {
        $this->view->post = $this->postsModel->findBySlug($slug);

        if (!$this->view->post)
        {
            $path = URL . 'posts';
            header('Location: ' . $path);
        }

        $this->view->render('posts/show');
    }
}
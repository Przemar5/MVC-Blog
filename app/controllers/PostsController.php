<?php


class PostsController extends Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->loadModel('posts');
        $this->loadModel('categories');
        $this->loadModel('tags');
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

    public function create_action()
    {
        $this->view->errors = Session::popMultiple(['title', 'label', 'slug', 'category_id', 'body']);
        $this->view->formAction = URL . 'posts/store';
        $this->view->categories = $this->categoriesModel->all();
        $this->view->tags = $this->tagsModel->all();
        $this->view->render('posts/create');
    }

    public function store_action()
    {
        if (Input::isPost())
        {
            $this->postsModel->populate($_POST);

            if ($this->postsModel->check())
            {
                echo 'good';
            }
            else
            {
                Session::setMultiple($this->postsModel->errors);

                $path = URL . 'posts/create';
                header('Location: ' . $path);
            }
        }
    }
}
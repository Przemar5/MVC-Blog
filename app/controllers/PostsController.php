<?php


class PostsController extends Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->loadModel('posts');
        $this->loadModel('categories');
        $this->loadModel('tags');
        $this->loadModel('users');
	}
	
	public function index_action()
	{
	    $this->view->posts = $this->postsModel->last(5, true);

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
        if (Input::isPost())
        {
            $this->verifyCreated();
            $this->view->errors = $this->postsModel->popErrors();
        }
		
        $this->view->post = $this->postsModel->populate($_POST);
        $this->view->categories = $this->categoriesModel->all();
        $this->view->tags = $this->tagsModel->all();
        $this->view->render('posts/create');
    }

    public function edit_action($slug)
    {
        if (Input::isPost())
        {
			$this->verifyUpdated($slug);
            $this->view->errors = $this->postsModel->popErrors();
			$this->view->post = $this->postsModel->populate($_POST);
        }
		else 
		{
			$this->view->post = $this->postsModel->findBySlug($slug);
		}
		
        $this->view->categories = $this->categoriesModel->all();
        $this->view->tags = $this->tagsModel->all();
        $this->view->render('posts/edit');
    }

    private function verifyCreated()
    {
        $this->postsModel->populate($_POST);

        if ($this->postsModel->check() && $this->postsModel->save())
        {
            Session::set('last_action', 'Your post had been added successfully.');

            $path = URL . 'posts';
            header('Location: ' . $path);
        }
    }

    private function verifyUpdated($slug)
    {
		$post = $this->postsModel->findBySlug($slug);
		
        $this->postsModel->populate($post, ['id', 'user_id']);
        $this->postsModel->populate($_POST);
		
        if ($this->postsModel->check(true) && $this->postsModel->save())
        {
            Session::set('last_action', 'Your post had been added successfully.');

            $path = URL . 'posts';
            header('Location: ' . $path);
        }
    }
}
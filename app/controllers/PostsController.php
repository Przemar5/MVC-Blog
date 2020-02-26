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
	    $postsPerPage = 5;
	    $this->view->posts = $this->postsModel->lastFrom($postsPerPage, false, true);
	    $this->view->pagination = $this->preparePagination($postsPerPage);

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
            Router::redirect(URL . 'posts');
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

        $this->view->submitButtonValue = 'Create';
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

        $this->view->submitButtonValue = 'Edit';
        $this->view->categories = $this->categoriesModel->all();
        $this->view->tags = $this->tagsModel->all();
        $this->view->render('posts/edit');
    }
	
	public function delete_action($slug)
	{
		$post = $this->postsModel->findBySlug($slug);

		if (Session::exists(USER_SESSION_NAME) && Session::get(USER_SESSION_NAME) == $post->user_id)
		{
			$post->delete();
			Session::set('last_action', 'Post had been removed.');
		}

        Router::redirect('posts');
	}

    private function verifyCreated()
    {
        $this->postsModel->populate($_POST);

        if ($this->postsModel->check() && $this->postsModel->save())
        {
            Session::set('last_action', 'Your post had been added successfully.');
            Router::redirect('posts');
        }
    }

    private function verifyUpdated($slug)
    {
		$post = $this->postsModel->findBySlug($slug);
		
        $this->postsModel->populate($post, ['id', 'user_id']);
        $this->postsModel->populate($_POST);

        if ($this->postsModel->check(true) && $this->postsModel->save())
        {
            echo 'good';
            Session::set('last_action', 'You have updated post successfully.');
            Router::redirect('posts');
        }
        echo 'bad';
        die;
    }

    private function preparePagination($postsPerPage)
    {
        $posts = $this->postsModel->count();
        $tabsNumber = ceil($posts / $postsPerPage);

        return HTML::pagination($tabsNumber, 1, URL . 'posts?page=');
    }
}
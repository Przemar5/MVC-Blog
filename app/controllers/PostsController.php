<?php


class PostsController extends Controller
{
    private const POSTS_PER_PAGE = 5;
    private $_currentPage = 1;

	public function __construct()
	{
		parent::__construct();

		$this->loadModels(['posts', 'categories', 'tags', 'users']);
	}
	
	public function index_action()
	{
	    $this->_currentPage = (Input::get('page')) ? Input::get('page') : 1;

        $this->view->posts = $this->postsModel->lastFrom(self::POSTS_PER_PAGE, $this->_getPostIdOffset());
	    $this->view->pagination = $this->_preparePagination();
        $this->postsModel->lastSelectId();

        if (count($this->view->posts))
        {
            foreach ($this->view->posts as $post)
            {
                $post->truncateText(600);
				
				if ($post->tags)
				{
					$post->prepareForDisplay();
				}
            }
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
		
		if ($this->view->post->tags)
		{
			$this->view->post->prepareForDisplay();
		}

        $this->view->render('posts/show');
    }

    public function create_action()
    {
        if (Input::isPost())
        {
            $this->_verifyCreated();
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
			$this->_verifyUpdated($slug);
            $this->view->errors = $this->postsModel->popErrors();
			$this->view->post = $this->postsModel->populate($_POST);
        }
		else 
		{
			$this->view->post = $this->postsModel->findBySlug($slug);
		}

        $this->view->submitButtonValue = 'Edit';
		$this->view->post->prepareTagIds();
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

    private function _verifyCreated()
    {
        $this->postsModel->populate($_POST);

        if ($this->postsModel->check() && $this->postsModel->save())
        {
            Session::set('last_action', 'Your post had been added successfully.');
            Router::redirect('posts');
        }
    }

    private function _verifyUpdated($slug)
    {
		$post = $this->postsModel->findBySlug($slug);
        $this->postsModel->populate($post, ['id', 'user_id']);
        $this->postsModel->populate($_POST);
		
        if ($this->postsModel->check(true) && $this->postsModel->save())
        {
            Session::set('last_action', 'You have updated post successfully.');
            Router::redirect('posts');
        }
    }

    private function _preparePagination()
    {
        $posts = $this->postsModel->count();
        $tabsNumber = ceil($posts / self::POSTS_PER_PAGE);

        return HTML::pagination($tabsNumber, (int) $this->_currentPage, URL . 'posts?page=');
    }

    private function _getPostIdOffset()
    {
        return $this->postsModel->getIds()[($this->_currentPage - 1) * self::POSTS_PER_PAGE];

        return ($this->_currentPage - 1) * self::POSTS_PER_PAGE + 1;
    }
}
<?php


class PostsController extends Controller
{
    private const POSTS_PER_PAGE = 5;
    private const COMMENTS_PER_POST = 5;
    private $_currentPage = 1, $_tagNames, $_category, $_params, $_ids, $_commentIds,
            $_lastCommentId = 0, $_commentsNumber = self::COMMENTS_PER_POST, $_noscript = false;
	private $_urlParams = [
		'tag' => ['tags', 'name']
	];

	public function __construct()
	{
		parent::__construct();

		$this->loadModels(['posts', 'categories', 'postsCategories', 'tags', 'users', 'comments', 'posts_comments', 'comments_comments']);
	}

	public function index_action()
	{
	    $this->_currentPage = (Input::get('page')) ? Input::get('page') : 1;

		if (Input::isGet())
		{
			if ($this->_extractGet('tag', '_tagNames', true))
			{
				$this->_findPosts();
			}
			else
			{
				$this->_ids = $this->postsModel->getIds();
				$this->view->posts = $this->postsModel->lastFrom(self::POSTS_PER_PAGE, $this->_getPostIdOffset());
			}
		}

	    $this->view->pagination = $this->_preparePagination();
        $this->postsModel->lastSelectId();

		ArrayHelper::callMethod($this->view->posts, 'truncateText', [600]);
		ArrayHelper::callMethod($this->view->posts, 'prepareForDisplay');

		$this->view->render('posts/index');
	}

	private function _extractGet($get, $property, $multiple = false)
	{
		if (empty($_GET[$get]))
		{
			return false;
		}
		return $this->{$property} = Input::get($get);
	}

	private function _findPosts()
	{
		if (!empty($this->_tagNames))
		{
			if (!$this->view->tags = ArrayHelper::callForArgs($this->tagsModel, 'findByName', $this->_tagNames))
			{
				Router::redirect(URL . 'posts');
			}

			$params = [
				'data' => [
					'tags' => [
						'name' => $this->_tagNames
					]
				]
			];

			$this->_ids = $this->postsModel->idDescFor($params);
			$this->view->posts = $this->postsModel->lastFromFor(self::POSTS_PER_PAGE, $this->_getPostIdOffset(), $params);
		}
	}

	public function show_action($slug)
    {
        if (!$this->view->post = $this->postsModel->findBySlug($slug))
        {
            Router::redirect(URL . 'posts');
        }

        /*  I know it looks ugly, but i think using redirect before rendering the page
            is better than redirecting user after loading page content. */
        if (!isset($_GET['noscript']))
        {
            Router::checkNoscript();
        }

        if (Input::isGet())
        {
            $this->_extractGet('noscript', '_noscript');

            if (!$this->_extractGet('comments', '_commentsNumber', true))
            {
                $this->_commentsNumber = self::COMMENTS_PER_POST;
            }
        }

		if (Input::isPost() && !empty($_POST))
		{
			$this->commentsModel->populate($_POST);

			if ($this->commentsModel->check() && $this->commentsModel->save())
			{
				Session::set('last_action', 'Your comment had been added successfully.');
			}
			else
			{
			    Session::set('last_action', "Your comment wasn't added.");
			    $this->view->errors = $this->commentsModel->popErrors();
			}
		}

		$this->view->post->prepareForDisplay();
		$this->view->submitButtonValue = 'Create';

		// Loading comments
		$params = [
		    'values' => '*',
		    'order' => 'id DESC',
		    'limit' => self::COMMENTS_PER_POST,
		];

        if (!$this->_noscript)
        {
            //$this->view->comments = $this->commentsModel->findForParent($this->view->post->id, null, $params, true);
        }
        else
        {
            $this->view->comments = $this->commentsModel->getCommentsTree($this->view->post->id, ['order' => 'id DESC']);
        }

        $this->view->render('posts/show');
    }

    public function create_action()
    {
        if (Input::isPost())
        {
            $this->_verifyCreated();
            $this->view->errors = $this->postsModel->popErrors() ?? [];
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
            $this->view->errors = $this->postsModel->popErrors() ?? [];
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
			$post->delete('', true);
			Session::set('last_action', 'Post had been removed.');
		}

        Router::redirect('posts');
	}

	public function category_action($slug)
	{
		$this->_currentPage = (Input::get('page')) ? Input::get('page') : 1;

		if (!$this->view->category = $this->categoriesModel->findBySlug($slug))
		{
			$this->view->render('error/404');
			exit;
		}

		$postIds = $this->postsCategoriesModel->postIdsByCategoryId($this->view->category->id, 'post_id DESC');
		$this->view->posts = $this->postsModel->lastFromByCategoryId(self::POSTS_PER_PAGE,
													   				$this->view->category->id, true);
		$this->view->pagination = $this->_preparePagination();

		ArrayHelper::callMethod($this->view->posts, 'getAdditionalInfo');
		ArrayHelper::callMethod($this->view->posts, 'truncateText', [600]);
		ArrayHelper::callMethod($this->view->posts, 'prepareForDisplay');

		$this->view->render('posts/category');
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
        $post->populate($_POST);

        if ($post->check(true) && $post->save())
        {
            Session::set('last_action', 'You have updated post successfully.');
            Router::redirect('posts');
        }
    }

    private function _preparePagination()
    {
		$tabsNumber = ceil(count($this->_ids) / self::POSTS_PER_PAGE);

		if (Input::isGet() && !empty($_GET['page']))
		{
			list($urlStart, $urlEnd) = URL::splitUrl('page');

			return HTML::pagination($tabsNumber, (int) $this->_currentPage, $urlStart, $urlEnd);
		}
		else
		{
			return HTML::pagination($tabsNumber, (int) $this->_currentPage, URL::actualUrl());
		}
    }

    private function _prepareLoadMore()
    {
        $loadCommentsUrl = URL . 'comments/load?post=' . $this->view->post->id . '&parent=0&comments=' . ($this->_commentsNumber + self::COMMENTS_PER_POST);

		return HTML::link(['text' => 'Load More Comments', 'id' => 'loadComments', 'class' => 'btn btn-block btn-primary', 'href' => $loadCommentsUrl]);
    }

    private function _getPostIdOffset($params = [], $model = 'postsModel', $method = 'getIds')
    {
		return $this->_ids[($this->_currentPage - 1) * self::POSTS_PER_PAGE];
    }
}
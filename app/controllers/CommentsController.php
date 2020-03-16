<?php


class CommentsController extends Controller
{
    private const COMMENTS_PER_LOAD = 5;
    private $_postId, $_parentId;

	public function __construct()
	{
		parent::__construct();

		$this->loadModels(['posts', 'comments', 'users']);
	}

	public function create_action()
	{
	    if (Input::isPost())
        {
            $this->_verifyCreated();
            $this->view->errors = $this->commentsModel->popErrors() ?? [];
            $this->view->comment = $this->commentsModel->populate($_POST);
        }

	    $postId = $this->postsModel->findById(Input::get('post'), ['values' => 'id'], false)->id;
        if (!$postId)
            return false;

        $parentId = Input::get('parent');
        if ($parentId != 0 && !$this->commentsModel->findById($parentId, ['values' => 'id'], false)->id)
            return false;

        $this->view->comment->post_id = $postId;
        $this->view->comment->parent_id = $parentId;
        $this->view->submitButtonValue = 'Create';
	    $this->view->render('comments/create');
	}

	public function edit_action($id)
	{
	    if (Input::isPost())
	    {
	        $this->_verifyUpdated($id);
            $this->view->errors = $this->commentsModel->popErrors() ?? [];
            $this->view->comment = $this->commentsModel->populate($_POST);
	    }

	    if (!$this->view->comment = $this->commentsModel->findById($id))
	    {
	        Router::redirect('posts');
	    }

        $this->view->submitButtonValue = 'Edit';
	    $this->view->render('comments/edit');
	}

    public function load_action()
    {
        ini_set('display_errors', 'Off');

        $postId = $this->postsModel->findById(Input::get('post'), ['values' => 'id'], false)->id;
        if (!$postId)
            return false;

        $parentId = Input::get('parent');
        if ($parentId != 0 && !$this->commentsModel->findById($parentId, ['values' => 'id'], false)->id)
            return false;

        $amount = (int) Input::get('comments');
        $offset = ($amount >= self::COMMENTS_PER_LOAD) ? $amount - self::COMMENTS_PER_LOAD : 0;

        $params = [
            'limit' => self::COMMENTS_PER_LOAD,
		    'order' => 'id DESC',
            'offset' => $offset
        ];
        $this->view->comments = $this->commentsModel->findForParent($postId, $parentId, $params, false);

        $result = preg_replace('/^[^\[]*(?!\[)/m', '', json_encode($this->view->comments));
        //$result = json_encode(json_decode($result), JSON_PRETTY_PRINT);

        echo $result;
    }

    public function delete_action($id)
    {
        if (!is_numeric($id) || !$post = $this->commentsModel->findById($id))
        {
            Router::redirect('posts');
        }

        if (Session::exists(USER_SESSION_NAME) && Session::get(USER_SESSION_NAME) == $post->user_id)
        {
            $post->delete('', true);
            Session::set('last_action', 'Comment had been removed.');
        }

        Router::redirect('posts');
    }

    public function form_action()
    {
        ini_set('display_errors', 'Off');

        $postId = $this->postsModel->findById(Input::get('post'), ['values' => 'id'], false)->id;
        if ($postId)
            $this->view->comment->post_id = $postId;

        $parentId = Input::get('parent');
        if ($parentId == 0 || !$this->commentsModel->findById($parentId, ['values' => 'id'], false)->id)
            $this->view->comment->parent_id = $parentId;

        $path = ROOT . DS. 'app' . DS . 'views' . DS . 'comments' . DS . 'partials' . DS . 'form.php';

        require($path);
    }

    private function _verifyCreated()
    {
        $comment = new $this->commentsModel;
        $comment->populate($_POST);

        if ($comment->check() && $comment->save())
        {
            $slug = $this->postsModel->findById($comment->post_id, ['values' => 'slug'])->slug;
            Session::set('last_action', 'Your comment had been added successfully.');
            Router::redirect('posts/show/' . $slug);
        }
    }

    private function _verifyUpdated($id)
    {
        if (!is_numeric($id) || !$comment = $this->commentsModel->findById($id))
        {
            Router::redirect('posts');
        }
        $comment->populate($_POST);

        if ($comment->check(true) && $comment->save())
        {
            Session::set('last_action', 'You have updated comment successfully.');
            Router::redirect('posts');
        }
    }

	private function _extractGet($get, $multiple = false)
	{
		if (empty($_GET[$get]))
		{
			return false;
		}

		return Input::get($get);
	}
}
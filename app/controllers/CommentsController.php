<?php


class CommentsController extends Controller
{
    private $_ids, $_lastCommentId;
    private const COMMENTS_PER_LOAD = 5;
    private static $_commentsNumber = 5, $_disabled = false;

	public function __construct()
	{
		parent::__construct();

		$this->loadModels(['posts', 'comments', 'users']);
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

    public function load_action($postSlug, $amount, $parentId = 0)
    {
        if ($postId = $this->postsModel->findBySlug($postSlug, 'id', false)->id)
        {
            $amount = (int) $amount;
            $this->_ids = $this->commentsModel->rootIdsForPostDesc($postId);
            $this->_ids = array_reverse(ArrayHelper::flattenSingles($this->commentsModel->findForParent($postId, null, 'id')));

            if ($amount >= count($this->_ids) + self::COMMENTS_PER_LOAD)
            {
                exit;
            }

            $lastId = $this->_ids[($amount - self::COMMENTS_PER_LOAD) - 1] ?? null;
            $this->view->comments = array_reverse($this->commentsModel->findByIds(array_splice($this->_ids, 0, self::COMMENTS_PER_LOAD)));
            $this->_lastCommentId = ArrayHelper::last($this->view->comments);

            echo preg_replace('/^[^\[]*(?=\[)/m', '', json_encode($this->view->comments));
        }
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

    private function _verifyUpdated($id)
    {
        if (!is_numeric($id) || !$comment = $this->commentsModel->findById($id))
        {
            Router::redirect('posts');
        }
        $comment->populate($_POST);

        if ($comment->check(true) && $comment->save())
        {
            Session::set('last_action', 'You have updated post successfully.');
            Router::redirect('posts');
        }
    }
}
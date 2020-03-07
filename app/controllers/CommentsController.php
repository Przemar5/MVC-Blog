<?php


class CommentsController extends Controller
{
    private $_ids, $_commentsNumber, $_lastCommentId;
    private const COMMENTS_PER_LOAD = 5;

	public function __construct()
	{
		parent::__construct();

		$this->loadModels(['posts', 'comments']);
	}

    public function load_action($postSlug, $amount)
    {
        if ($postId = $this->postsModel->findBySlug($postSlug, 'id', false))
        {
            $this->_commentsNumber = $amount;
            $this->_ids = $this->commentsModel->idDescForPost($postId);
            $this->view->comments = $this->commentsModel->lastForPost($this->_commentsNumber, $postId);
            $this->_lastCommentId = ArrayHelper::last($this->view->comments)->id;

            echo json_encode($this->view->comments);
        }

    }
}
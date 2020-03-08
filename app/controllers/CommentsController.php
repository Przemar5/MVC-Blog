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
        if ($postId = $this->postsModel->findBySlug($postSlug, 'id', false)->id)
        {
            $this->_commentsNumber = $amount;
            $this->_ids = $this->commentsModel->idDescForPost($postId);
            $this->view->comments = $this->commentsModel->lastFromForPost(self::COMMENTS_PER_LOAD,
                                                                            $this->_ids[($amount - self::COMMENTS_PER_LOAD) - 1], $postId);
            $this->_lastCommentId = ArrayHelper::last($this->view->comments)->id;

            echo json_encode($this->view->comments);
        }

    }
}
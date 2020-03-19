<?php


class PostsCommentsModel extends Model
{
    public $id, $parent_id, $child_id, $is_post, $deleted;

    public function __construct()
    {
        parent::__construct('posts_comments');
        $this->_softDelete = true;
    }
	
	public function postIdForCommentId($commentId)
	{
		$params = [
			'values' => 'post_id',
			'conditions' => 'comment_id = ?',
			'bind' => [$commentId]
		];
		
		return ArrayHelper::flattenSingles($this->findFirst($params, false));
	}
}
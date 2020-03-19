<?php


class ParentsCommentsModel extends Model
{
    public function __construct()
    {
        parent::__construct('parents_comments');
    }

    public function countChildrenFor($parentId)
    {
        $params = [
            'conditions' => 'parent_id = ?',
            'bind' => [$parentId]
        ];

        return $this->count($params);
    }
	
	public function parentIdForCommentId($commentId)
	{
		$params = [
			'values' => 'parent_id',
			'conditions' => 'comment_id = ?',
			'bind' => [$commentId]
		];
		
		return ArrayHelper::flattenSingles($this->findFirst($params, false));
	}
}
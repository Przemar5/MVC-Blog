<?php


class PostsTagsModel extends Model
{
    public $tag_ids = [];

    public function __construct()
    {
        parent::__construct('posts_tags', false);
    }

    public function tagsForPost($postId)
    {
        $this->tag_ids = $this->find(['values' => 'tag_id', 'conditions' => 'post_id = ?', 'bind' => [$postId]], false);
        $this->tag_ids = ArrayHelper::flattenSingles($this->tag_ids);
		
		if (empty($this->tag_ids))
		{
			return true;
		}
		
		$length = count($this->tag_ids);
		
		for ($i = 0; $i < $length; $i++)
		{
			$conditions[$i] = ' id = ?';
		}
		
		$conditions = implode(' OR ', $conditions);
		
		return ModelMediator::make('tags', 'find', [['conditions' => $conditions, 'bind' => $this->tag_ids], true]);
    
	}

    public function tagNamesForPost($postId)
    {
        $this->tagIds = $this->find(['values' => 'tag_id', 'conditions' => 'post_id = ?', 'bind' => [$postId]], true);
        $this->tagIds = ArrayHelper::flattenSingles($this->tagIds);

        return $this->tagIds;
    }

    public function insertMultiple($data)
    {
        return $this->_db->insertMultiple($this->_table, $data);
    }
	
	public function deleteForPost($postId)
	{
		$sql = 'DELETE FROM ' . $this->_table . ' WHERE post_id = ?';
		
		return !$this->_db->query($sql, [$postId])->error();
	}
}
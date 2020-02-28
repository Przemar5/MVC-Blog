<?php


class PostsTagsModel extends Model
{
    public $tagIds;

    public function __construct()
    {
        parent::__construct('posts_tags');

        $this->loadModel('posts_tags');
        $this->loadModel('tags');
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
}
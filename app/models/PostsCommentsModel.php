<?php


class PostsCommentsModel extends Model
{
    public $id, $parent_id, $child_id, $is_post, $deleted;

    public function __construct()
    {
        parent::__construct('posts_comments');
        $this->_softDelete = true;
    }
}
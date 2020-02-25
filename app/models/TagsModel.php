<?php


class TagsModel extends Model
{
    public $id, $name;

    public function __construct()
    {
        parent::__construct('tags');
    }
}

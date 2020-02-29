<?php


class CategoriesModel extends Model
{
    public $id, $name, $slug, $deleted;

    public function __construct()
    {
        parent::__construct('categories');
    }
}

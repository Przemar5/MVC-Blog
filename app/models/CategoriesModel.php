<?php


class CategoriesModel extends Model
{
    public $id, $name, $slug;

    public function __construct()
    {
        parent::__construct('categories');
    }
}

<?php


class PostsCategoriesModel extends Model
{
    public $post_id, $category_id;
	
	private $validationRules = [
        'category_id' => [
            'required' => ['msg' => 'Category is required.'],
            'numeric' => ['msg' => 'Invalid category.'],
            'regex' => ['args' => ['[0-9]{1,7}'], 'msg' => 'Invalid category.'],
            'exists' => ['args' => ['categories', 'id'], 'msg' => "Category doesn't exist."]
        ],
	];

    public function __construct()
    {
        parent::__construct('posts_categories');

        $this->loadModel('categories');
    }

    public function categoryForPost($postId)
    {
        $this->categories = $this->find(['values' => 'category_id', 'conditions' => 'post_id = ?', 'bind' => [$postId]], true);
        $this->categories = ArrayHelper::flattenSingles($this->categories);
//        dd($this->tagIds);
        return $this->categories;
    }
}
<?php


class PostsCategoriesModel extends Model
{
    public $id, $post_id, $category_id;
	
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

    public function categoryForPost($postId, $class = true)
    {
        $this->category_id = $this->findFirst(['values' => 'category_id', 'conditions' => 'post_id = ?', 'bind' => [$postId]], false);
		$this->category_id = ArrayHelper::flattenSingles($this->category_id);
		
		return $this->categoriesModel->findById($this->category_id);
    }
	
	public function updateCategoryForPost($postId, $categoryId)
	{
		if (empty($postId) || empty($categoryId))
        {
            return false;
        }
		
		$sql = 'UPDATE ' . $this->_table . ' SET category_id = ' . $categoryId . ' WHERE post_id = ' . $postId;
        
		return !$this->_db->query($sql, [$categoryId])->error();
	}
	
	public function countPostsForCategory($categoryId)
	{
		return $this->count(['conditions' => 'category_id = ?', 'bind' => $categoryId]);
	}
}
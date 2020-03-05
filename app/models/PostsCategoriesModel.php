<?php


class PostsCategoriesModel extends Model
{
    public $id, $post_id, $category_id, $post_ids;
	
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
        parent::__construct('posts_categories', false);
    }

    public function categoryForPost($postId, $class = true)
    {
        $this->category_id = $this->findFirst(['values' => 'category_id', 'conditions' => 'post_id = ?', 'bind' => [$postId]], false);
		$this->category_id = ArrayHelper::flattenSingles($this->category_id);
		
		return ModelMediator::make('categories', 'findById', [$this->category_id]);
    }
	
	public function postIdsForCategoryId($categoryId, $order = 'post_id ASC')
	{
		return ArrayHelper::flattenSingles($this->find(['conditions' => 'category_id = ' . $categoryId, 'order' => $order]));
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
		return $this->count(['conditions' => 'category_id = ?', 'bind' => [$categoryId]]);
	}
	
	
	
	public function postIdsByCategoryId($categoryId)
	{
		$data = [
			'values' => 'post_id', 
			'conditions' => 'category_id = ?', 
			'bind' => [$categoryId]
		];
		
		$this->post_ids = ArrayHelper::flattenSingles($this->find($data, false));
		
		return $this->post_ids;
	}
	
	public function postsByCategoryId($categoryId)
	{
		$post_ids = $this->postIdsByCategory($categoryId);
	
		$conditions = '';
		
		if (!empty($post_ids))
		{
			foreach ($post_ids as $post_id)
			{
				$conditions .= ' id = ? OR';
			}
		
			$conditions = rtrim($conditions, ' OR');
		}
		
		return ModelMediator::make('posts', 'find', [['conditions' => $conditions, 'bind' => $post_ids]]);
	}
}
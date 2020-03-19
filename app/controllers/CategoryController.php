<?php


class CategoryController extends Controller
{
	private const POSTS_PER_PAGE = 10;
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->loadModels(['categories', 'postsCategories']);
	}
	
	public function index_action()
	{
		$this->view->categories = $this->categoriesModel->find();
		$this->view->render('category/index');
	}
	
	public function show_action($slug)
	{
		if ($this->view->category = $this->categoriesModel->findBySlug($slug))
		{
			$this->view->posts = $this->postsCategoriesModel->postsByCategoryId($this->view->category->id);
			$this->_preparePagination(ceil(count($this->view->posts) / self::POSTS_PER_PAGE));
			$this->view->render('category/show');
		}
		else 
		{
			$this->view->render('error/404');
		}
	}
	
	public function create_action()
	{
		if (Input::isPost())
		{
            $this->_verifyCreated();
            $this->view->errors = $this->categoriesModel->popErrors() ?? [];
        	$this->view->category = $this->categoriesModel->populate($_POST);
		}
		
		$this->view->render('category/create');
	}
	
	public function edit_action($slug)
	{
		if (Input::isPost())
		{
            $this->_verifyUpdated($slug);
            $this->view->errors = $this->categoriesModel->popErrors() ?? [];
        	$this->view->category = $this->categoriesModel->populate($_POST);
		}
		else 
		{
			$this->view->category = $this->categoriesModel->findBySlug($slug);
		}
		
        $this->view->submitButtonValue = 'Edit';
		$this->view->render('category/edit');
	}
	
	private function _verifyCreated()
	{
		$this->categoriesModel->populate($_POST);
		
		if ($this->categoriesModel->check() && $this->categoriesModel->save())
        {
            Session::set('last_action', 'New category was created successfully.');
            Router::redirect('category');
        }
	}

    private function _verifyUpdated($slug)
    {
		$category = $this->categoriesModel->findBySlug($slug);
        $this->categoriesModel->populate($category, ['id']);
        $this->categoriesModel->populate($_POST);
		
        if ($this->categoriesModel->check(true) && $this->categoriesModel->save())
        {
            Session::set('last_action', 'You have updated category successfully.');
            Router::redirect('category');
        }
    }

    private function _preparePagination($tabs)
    {
		if (Input::isGet() && !empty($_GET['page']))
		{
			list($urlStart, $urlEnd) = URL::splitUrl('page');

			return HTML::pagination($tabs, (int) $this->_currentPage, $urlStart, $urlEnd);
		}
		else
		{
			return HTML::pagination($tabs, (int) $this->_currentPage, URL::actualUrl());
		}
    }
}
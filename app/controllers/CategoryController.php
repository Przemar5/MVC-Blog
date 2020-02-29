<?php


class CategoryController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->loadModels(['categories', 'posts_categories']);
	}
	
	public function index_action()
	{
		$this->view->categories = $this->categoriesModel->all();
		$this->view->render('category/index');
	}
	
	public function show_action($id)
	{
		$this->view->category = $this->categoriesModel->findBySlug($id);
		dd($this->view->category);
		$this->view->render('category/show');
	}
}
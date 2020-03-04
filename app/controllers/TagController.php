<?php


class TagController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->loadModels(['tags', 'postsTags']);
	}
	
	public function index_action()
	{
		$this->view->tags = $this->tagsModel->find();
		$this->view->render('tag/index');
	}
	
	public function show_action($slug)
	{
		if ($this->view->category = $this->categoriesModel->findBySlug($slug))
		{
			$this->view->posts = $this->postsCategoriesModel->postsByCategoryId($this->view->category->id);
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
            $this->view->errors = $this->tagsModel->popErrors() ?? [];
        	$this->view->tag = $this->tagsModel->populate($_POST);
		}
		
		$this->view->render('tag/create');
	}
	
	public function edit_action($name)
	{
		if (Input::isPost())
		{
            $this->_verifyUpdated($name);
            $this->view->errors = $this->tagsModel->popErrors() ?? [];
        	$this->view->tag = $this->tagsModel->populate($_POST);
		}
		else 
		{
			$this->view->tag = $this->tagsModel->findByName($name);
		}
		
        $this->view->submitButtonValue = 'Edit';
		$this->view->render('tag/edit');
	}
	
	public function delete_action($name)
	{
		if (Session::exists(USER_SESSION_NAME))
		{
			$tag = $this->tagsModel->findByName($name);
			$tag->deleteWithDependencies();
			Session::set('last_action', 'Tag had been removed.');
		}

        Router::redirect('tag');
	}
	
	private function _verifyCreated()
	{
		$this->tagsModel->populate($_POST);
		
		if ($this->tagsModel->check() && $this->tagsModel->save())
        {
            Session::set('last_action', 'New tag was created successfully.');
            Router::redirect('tag');
        }
	}

    private function _verifyUpdated($name)
    {
		$category = $this->tagsModel->findByName($name);
        $this->tagsModel->populate($category, ['id']);
        $this->tagsModel->populate($_POST);
		
        if ($this->tagsModel->check(true) && $this->tagsModel->save())
        {
            Session::set('last_action', 'You have updated tag successfully.');
            Router::redirect('tag');
        }
    }
}
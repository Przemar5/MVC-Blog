<?php


class View
{
	protected 	$_head, 
				$_body, 
				$_siteTitle = SITE_TITLE, 
				$_outputBuffer, 
				$_layout = DEFAULT_LAYOUT;
	
	public function __construct()
	{
		//
	}
	
	public function render($path)
	{
		$viewArray = explode('/', $path);
		$viewString = implode(DS, $viewArray);
		
		$viewPath = ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php';
		$layout = ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->_layout . '.php';
		
		require_once($viewPath);
		require_once($layout);
	}
	
	public function content($type)
	{
		if ($type === 'head')
		{
			return $this->_head;
		}
		else if ($type === 'body')
		{
			return $this->_body;
		}
		
		return false;
	}
	
	public function start($type)
	{
		$this->_outputBuffer = $type;
		
		ob_start();
	}
	
	public function end()
	{
		if ($this->_outputBuffer === 'head')
		{
			$this->_head = ob_get_clean();
		}
		else if ($this->_outputBuffer === 'body')
		{
			$this->_body = ob_get_clean();
		}
	}
	
	public function siteTitle()
	{
		return $this->_siteTitle;
	}
	
	public function setSiteTitle($title)
	{
		$this->_siteTitle = $title;
	}
	
	public function setLayout($path)
	{
		$this->_layout = $path;
	}
	
	public function insert($path)
	{
		include_once ROOT . DS . 'app' . DS . 'views' . DS . $path . '.php';
	}
	
	public function partial($group, $partial)
	{
		include_once ROOT . DS . 'app' . DS . 'views' . DS . $group . DS . 'partials' . DS . $partial . '.php';
	}
}
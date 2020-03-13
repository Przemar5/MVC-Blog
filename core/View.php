<?php


class View
{
	protected 	$_head, 
				$_body, 
				$_siteTitle = SITE_TITLE, 
				$_outputBuffer, 
				$_layout = DEFAULT_LAYOUT,
				$_scripts = [];
	
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

	public function include($file)
    {
        include_once ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $file . '.php';
    }

    public function setScript($name1, $name2 = '', $attrs = [])
    {
        $attrs = HTML::stringifyAttrs($attrs);
        $directory = PathHelper::getDirectory(debug_backtrace()[0]['file']);
        $path = PathHelper::parentFolders($directory);
        $path = implode('/', explode(DS, $path));

        if (!empty($name2))
        {
            $path = preg_replace('/[0-9a-zA-Z _\-]*\/$/', $name1, $path);
            $url = URL . $path . '/' . 'js' . '/' . $name2 . '.js';
        }
        else
        {
            $url = URL . $path . 'js' . '/' . $name1 . '.js';
        }

        $this->_scripts[] = '<script type="text/javascript" ' . $attrs . ' src="' . $url . '"></script>';
    }

    public function setScripts($names)
    {
        $attrs = HTML::stringifyAttrs($inputData);
        $directory = PathHelper::getDirectory(debug_backtrace()[0]['file']);
        $path = PathHelper::parentFolders($directory);
        $path = implode('/', explode(DS, $path));

        if (!empty($names))
        {
            if (is_array($names))
            {
                foreach ($names as $name)
                {
                    if (is_array($name) && !empty($name))
                    {
                        $path = preg_replace('/[0-9a-zA-Z _\-]*\/$/', $name[0], $path);
                        $url = URL . $path . '/' . 'js' . '/' . $name[1] . '.js';
                    }
                    else
                    {
                        $url = URL . $path . 'js' . '/' . $name . '.js';
                    }

                    $this->_scripts[] = '<script type="text/javascript" ' . $attrs . ' src="' . $url . '"></script>';
                }
            }
            else
            {
                $url = URL . $path . 'js' . '/' . $names . '.js';
                $this->_scripts[] = '<script type="text/javascript" ' . $attrs . ' src="' . $url . '"></script>';
            }
        }
    }

    public function scripts()
    {
        if (empty($this->_scripts))
        {
            return false;
        }

        return implode("", $this->_scripts);
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
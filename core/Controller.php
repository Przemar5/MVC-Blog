<?php

//namespace Core;


class Controller
{
	public function __construct()
	{
		$this->view = new View;
	}

	public function loadModel($table)
    {
        $path = ROOT . DS . 'app' . DS . 'models' . DS . $table . 'Model.php';

        if (file_exists($path))
        {
            require_once $path;

            $modelName = $table . 'Model';
            $this->{$modelName} = new $modelName;
        }
    }
	
	public function view()
	{
		$this->view = new View;
		
		return $this->view;
	}
}
<?php

define('ROOT', __DIR__);

require_once 'config/config.php';

require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/HTML.php';
require_once 'core/Input.php';
require_once 'core/Validator.php';

require_once 'app/controllers/HomeController.php';


function d($data)
{
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}

//echo $_SERVER['REQUEST_URI'];
$url = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : '';


// Check regex match

if (!empty($url))
{
	$url = (!empty($url)) ? explode('/', trim($url, '/')) : $url = '';
	$len = count($url);
	
	$requestedFile = 'app/controllers/' . $url[0] . 'Controller.php';
	
	if (is_readable($requestedFile))
	{
		require_once $requestedFile;
		
		// Create controller
		$controllerName = $url[0] . 'Controller';
		$controller = new $controllerName;
		
		if (!empty($url[1]))
		{
			$methodName = $url[1] . '_action';
			
			if (method_exists($controller, $methodName))
			{
				// Call method
				$controller->{$methodName}();
			}
		}
		else 
		{
			$controller->index_action();
		}
	}
}
else 
{
	require_once 'app/controllers/HomeController.php';
	
	$controller = new HomeController;
	
	$controller->index_action();
}

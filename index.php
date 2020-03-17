<?php

session_start();

define('ROOT', __DIR__);

require_once 'config/config.php';

require_once 'core/ArrayHelper.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';
require_once 'core/Form.php';
require_once 'core/GraphHelper.php';
require_once 'core/Helper.php';
require_once 'core/HTML.php';
require_once 'core/Input.php';
require_once 'core/Model.php';
require_once 'core/ModelMediator.php';
require_once 'core/PathHelper.php';
require_once 'core/Router.php';
require_once 'core/Session.php';
require_once 'core/Token.php';
require_once 'core/URL.php';
require_once 'core/UserAgent.php';
require_once 'core/Validator.php';
require_once 'core/View.php';

require_once 'app/controllers/HomeController.php';


function d($data, $line = '', $function = '')
{
    echo '<pre>';

    if (gettype($data) === 'array' || gettype($data) === 'object')
    {
        var_dump($data);
    }
    else 
    {
        echo $data;
    }

    echo '</pre>';

    echo '<br>', $line, '<br>', $function;
    echo "\r\n";
}

function dd($data)
{
    d($data);
    die;
}


//echo $_SERVER['REQUEST_URI'];
$url = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : '';
//dd($_SESSION);
// Check regex match
if (!empty($url))
{
	$url = explode('/', trim($url, '/'));
	$len = count($url);
	$controllerPrefix = array_shift($url);
	$requestedFile = 'app/controllers/' . $controllerPrefix . 'Controller.php';
	
	if (is_readable($requestedFile))
	{
		require_once $requestedFile;

		// Create controller
		$controllerName = $controllerPrefix . 'Controller';
		$controller = new $controllerName;

		if (!empty($url))
		{
			$method= array_shift($url) . '_action';

			if (method_exists($controller, $method))
			{
				// Call method
                call_user_func_array([$controller, $method], $url);
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

<?php


class ModelMediator
{
	private static $models = [];
	
	public static function loadModel($name)
	{
		$path = ROOT . DS . 'app' . DS . 'models' . DS . $name . 'Model.php';

        if (file_exists($path))
        {
            require_once $path;

            $modelName = $name . 'Model';
            self::$models[$modelName] = new $modelName;
			
			return true;
        }
		
		return false;
	}
	
	public static function make($modelName, $methodName, $params = [])
	{
		$model = $modelName . 'Model';
		
		if (!isset(self::$models[$model]))
		{
			if (!self::loadModel($modelName))
			{
				return false;
			}
		}
		
		if (method_exists($model, $methodName))
		{
			return call_user_func_array([self::$models[$model], $methodName], $params);
		}
	}
	
//	public function __construct()
//	{
//		//
//	}
//
//	public function loadModel($name)
//	{
//		$path = ROOT . DS . 'app' . DS . 'models' . DS . $name . 'Model.php';
//
//        if (file_exists($path))
//        {
//            require_once $path;
//
//            $modelName = $name . 'Model';
//            $this->{$modelName} = new $modelName;
//			
//			return true;
//        }
//		
//		return false;
//	}
//	
//	public function make($modelName, $methodName, $params = [])
//	{
//		$model = $modelName . 'Model';
//		
//		if (!isset($this->{$model}))
//		{
//			if (!$this->loadModel($modelName))
//			{
//				return false;
//			}
//		}
//		
//		dd($this);
//		
//		if (method_exists($model, $methodName))
//		{
//			return call_user_func_array([$this->{$model}, $methodName], $params);
//		}
//	}
}
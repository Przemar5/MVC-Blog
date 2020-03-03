<?php


class GraphHelper
{
	public static function findPath($graph, $start, $end, $startColumn = '')
	{
		if (empty($graph))
		{
			return;
		}
		
		$path = [];
		
		for ($i = 0; $i < count($graph); $i++)
		{
			if (array_key_exists($start, $graph[$i]))
			{
				if (!empty($startColumn))
				{
					$path[$start] = [$startColumn, $graph[$i][$start]];
				}
				else 
				{
					$path[$start] = $graph[$i][$start];
				}
				
				unset($graph[$i][$start]);
				
				$possible = $graph[$i];
				unset($graph[$i]);
				
				foreach ($possible as $key => $value)
				{
					if ($key === $end)
					{
						$path[$end] = $value;

						return $path;
					}
					
					if ($result = self::findPath($graph, $key, $end))
					{
						$path[$key] = [$value, $result];
					}
				}
			}
		}
		
		$result = [];
		
		if (!empty($path))
		{
			foreach ($path as $key => $subArr)
			{
				if (is_array($subArr))
				{
					$c = 0;
					
					foreach ($subArr as $subSubArr)
					{
						if (is_array($subSubArr))
						{
							$tmp = array_pop($subArr);
							unset($path[$key][$c + 1]);
							array_push($path[$key], array_shift($subSubArr));
							reset($subSubArr);
							$path[key($subSubArr)] = $subSubArr[key($subSubArr)];
							
						}
					}
					
					$c++;
				}
			}
		}
		
		return $path;
	}
	
	public static function getPair($graph, $start)
	{
		
	}
	
	public static function linkExists($graph, $start, $end)
	{
//		$path = [];
//		
//		if ($result = self::findPairByKey($graph, $start))
//		{
//			$graph
//		}
//		
//		
//		for ($i = 0; $i < count($graph); $i++)
//		{
//			if (array_key_exists($start, $graph[$i]))
//			{
//				$path[$start] = $graph[$i][$start];
//				unset($graph[$i][$start]);
//				
//				$possible = $graph[$i];
//				unset($graph[$i]);
//				
//				if (empty($possible))
//				{
//					return;
//				}
//				
//				foreach ($possible as $key => $value)
//				{
//					d("POSSIBLE");
//					d($possible);
//					$path[] = self::findPath($graph, $key, $end);
//				}
//			}
//		}
	}
	
	public function findPairByKey($graph, $key)
	{
		foreach ($graph as $array)
		{
			if (array_key_exists($key, $array))
			{
				$result = [$key => $array[$key]];
				
				return $result;
			}
		}
	}
	
	public static function removeFromGraph($graph, $key)
	{
		
	}
}
<?php


class Validator
{
	private $_data = [], $_rules = [], $_errors = [], $_checkMultiple = false, 
			$_currentRule = [], $_currentField = null, $_currentValue = null, $_currentError = null, $_passed = false;
	
	
	public function __construct()
	{
		//
	}
	
//	public function check($data, $rules, $msgs = true)
//	{
//		$this->_data = $data;
//		$this->_rules = $rules;
//		$this->_passed = true;
//		
//		
//		foreach ($rules as $field => $fieldRules)
//		{
//			$this->_currentField = $field;
//			$this->_currentValue = $data[$field];
//			
//			if (array_key_exists($field, $data))
//			{
//				foreach ($fieldRules as $method => $params)
//				{
//					d($fieldRules);
//					
//					if ($method === 'multiple' && $params === true)
//					{
//						$this->_checkMultiple = true;
//					}
//					else
//					{
//						$this->_checkMultiple = false;
//					}
//					
//					if (method_exists($this, $method))
//					{
//						if ($this->_checkMultiple)
//						{
//							 $args = (!empty($params['args']))
//                                ? array_merge([$data[$field]], $params['args']) : [$data[$field]];
//							d($args);
//						}
//					}
//				}
//			}
//		}
//	}
//	
//	private function execute()
//	{
//		$args = (!empty($params['args']))
//			? array_merge([$data[$field]], $params['args']) : [$data[$field]];
//
//		if (!call_user_func_array([$this, $method], $args))
//		{
//			$this->_passed = false;
//
//			if ($msgs)
//			{
//				$this->setError($field, $params['msg']);
//			}
//
//			break 1;
//		}
//	}
	
	public function check($data, $rules, $msgs = true)
	{
		$this->_data = $data;
		$this->_rules = $rules;
		$this->_passed = true;
		
		foreach ($rules as $field => $fieldRules)
		{
			if (array_key_exists($field, $data))
			{
				$this->_currentField = $field;
				
				foreach ($fieldRules as $method => $params)
				{
					if ($method === 'multiple' && $params === true)
					{
						$this->_checkMultiple = $field;
					}
					
					if ($this->_checkMultiple === $this->_currentField)
					{
						$values = $data[$field];
						$i = 0;
						
						for ($i; $i < count($values); $i++)
						{
							if (method_exists($this, $method))
							{
								$args = (!empty($params['args']))
									? array_merge([0 => $values[$i]], $params['args']) : [$values[$i]];

								if (!call_user_func_array([$this, $method], $args))
								{
									$this->_passed = false;

									if ($msgs)
									{
										$this->setError($field, $params['msg']);
									}

									break 2;
								}
							}
						}
					}
					else 
					{
						$this->_checkMultiple = false;
						
						if (method_exists($this, $method))
						{
							$args = (!empty($params['args']))
								? array_merge([$data[$field]], $params['args']) : [$data[$field]];

							if (!call_user_func_array([$this, $method], $args))
							{
								$this->_passed = false;

								if ($msgs)
								{
									$this->setError($field, $params['msg']);
								}

								break 1;
							}
						}
					}
				}
			}
		}

		return $this->_passed;
	}
	
	public function required($value)
	{
		return !empty($value);
	}
	
	public function min($value, $min)
	{
	    return strlen($value) >= $min;
	}
	
	public function max($value, $max)
	{
		return strlen($value) <= $max;
	}
	
	public function regex($value, $pattern)
	{
		return preg_match('/^' . $pattern . '$/', $value);
	}

	public function numeric($value)
	{
		return is_numeric($value);
	}
	
	public function exists($value, $table, $column)
	{
		return Database::getInstance()->query('SELECT * FROM ' . $table . ' WHERE ' . $column . ' = ' . $value . ' LIMIT 1');	
	}
	
	public function match($value1, $value2)
	{
		return $value1 == $value2;
	}
	
	public function unique($value, $table, $column, $id = null)
	{
		$model = Helper::tableToModelName($table);
		$params['conditions'] = $column . ' = ?';
		$params['conditions'] .= ($id) ? ' AND id != ?' : '';
		$params['bind'] = ($id) ? [$value, $id] : [$value];
		
		return empty(ModelMediator::make($model, 'count', [$params]));
	}
	
	public function errors()
	{
		return $this->_errors;
	}
	
	public function currentError()
	{
		return $this->_currentError;
	}

	public function passed()
    {
        return $this->_passed;
    }

    private function setError($field, $msg)
    {
        $this->_currentError = $msg;
        $this->_errors[$field] = $msg;
    }
}
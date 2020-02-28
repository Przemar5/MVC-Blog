<?php


class Validator
{
	private $_data = [], $_rules = [], $_errors = [], $_currentError = null, $_passed = false;
	
	
	public function __construct()
	{
		//
	}
	
	public function check($data, $rules, $msgs = true)
	{
		$this->_data = $data;
		$this->_rules = $rules;
		$this->_passed = true;
		
		foreach ($rules as $field => $fieldRules)
		{
			if (array_key_exists($field, $data))
			{
				foreach ($fieldRules as $method => $params)
				{
					if (method_exists($this, $method))
					{
					    if (is_array($data) || is_object($data))
                        {
                            $args = (!empty($params['args']))
                                ? array_merge([$data[$field]], $params['args']) : [$data[$field]];

                            foreach ($args as $arg)
                            {
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
                        else
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
<?php


class Validator
{
	private $_data = [], $_rules = [];
	
	public function __construct()
	{
		//
	}
	
	public function check($data, $rules)
	{
		$this->_data = $data;
		$this->_rules = $rules;
		
		d($rules);
		
		foreach ($rules as $field => $fieldRules)
		{
			if (array_key_exists($field, $data))
			{
				d($fieldRules);
				
				foreach ($fieldRules as $method => $params)
				{
					if (property_exists($this, $method))
					{
						echo $method($data[$key]);
					}
				}
			}
		}
	}
	
	public function required($value)
	{
		return !empty($value);
	}
}
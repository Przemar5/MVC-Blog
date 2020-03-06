<?php


class Model
{
    protected $_db, $_table, $_softDelete = true, $lastSelectId, $modelMediator, $_errors = [], $formValues = [];

    public function __construct($table, $softDelete = true)
    {
        $this->_table = $table;
        $this->_db = Database::getInstance();
		$this->_softDelete = $softDelete;
    }

    protected function loadModel($name)
    {
        $path = ROOT . DS . 'app' . DS . 'models' . DS . $name . 'Model.php';

        if (file_exists($path))
        {
            require_once $path;

            $modelName = $name . 'Model';
            $this->{$modelName} = new $modelName;
        }
    }

    public function populate($data, $values = [])
    {
		$values = (!empty($values)) ? $values : $this->formValues;
		
		if (!empty($data) && count($data))
		{
			foreach ($data as $key => $value)
			{
				if (property_exists($this, $key) && in_array($key, $values))
				{
					$this->{$key} = $value;
				}
			}
		}
		
		return $this;
    }

    public function find($params = [], $class = true, $additionalInfo = true)
    {
		$class = ($class) ? get_class($this) : false;
		
		$result = (array) $this->_db->find($this->_table, $params, $class);
		
		if ($additionalInfo && !empty($result))
		{
			$class = get_class($this);

			if ($result[0] instanceof $class && method_exists($this, 'getAdditionalInfo'))
			{
				foreach ($result as $row)
				{
					$row->getAdditionalInfo();
				}
			}
		}
		
        return $result;
    }

    public function findFirst($params = [], $class = true, $additionalInfo = true)
    {
		$class = ($class) ? get_class($this) : false;
		
		$result = $this->_db->findFirst($this->_table, $params, $class);
		
		if ($additionalInfo)
		{
			if (!empty($result))
			{
				if ($class && method_exists($this, 'getAdditionalInfo'))
				{
					$result->getAdditionalInfo();
				}
			}
		}
		
        return $result;
    }

    public function findById($id, $params = [], $class = true, $additionalInfo = true)
    {
		$class = ($class) ? get_class($this) : false;
		
        $params['conditions'] = 'id = ? ';
        $params['bind'] = [$id];

        return $this->findFirst($params, $class, $additionalInfo);
    }

    public function all($values = [], $class = false, $additionalInfo = true)
    {
        $class = ($class) ? get_class($this) : false;

        $result = $this->_db->all($this->_table, $values, $class);
		
		if ($additionalInfo)
		{
			if (!empty($result))
			{ 
				$class = get_class($this);

				if ($result[0] instanceof $class && method_exists($this, 'getAdditionalInfo'))
				{
					foreach ($result as $row)
					{
						$row->getAdditionalInfo();
					}
				}
			}
		}
		
		return $result;
    }

    public function last($amount = 1, $params = [], $class = true, $additionalInfo = true)
    {
        if (is_numeric($amount))
        {
            $params['limit'] = $amount;
            $params['order'] = ' id DESC';

            $result = $this->find($params, $class);
            $this->lastSelectId = $this->_db->lastSelectID();

			if ($additionalInfo)
			{
//				if (!empty($result))
//				{ 
//					$class = get_class($result);
//
//					if ($result instanceof $class && method_exists($this, 'getAdditionalInfo'))
//					{
//						$result->getAdditionalInfo();
//					}
//				}
			}
			
            return $result;
        }
        return [];
    }

    public function lastFrom($amount = 1, $from = 0, $params = [], $class = true, $additionalInfo = true)
    {
        if (is_numeric($amount))
        {
			if (!empty($params['conditions']))
			{
				$params['conditions'] = '(' . $params['conditions'] . ') AND id <= ' . (string) $from;
			}
			else
			{
            	$params['conditions'] = (!empty($from)) ? 'id <= ' . (string) $from : '';
			}

            $params['limit'] = $amount;
            $params['order'] = ' id DESC';

            $result = $this->find($params, $class);
			
            $this->lastSelectId = $this->_db->lastSelectId();
			
			if ($additionalInfo)
			{
				if (!empty($result))
				{ 
					$isClassObject = true;
					
					if ($class)
					{
						$class = get_class($this);
						$isClassObject = $result[0] instanceof $class;
					}

					if ($isClassObject && method_exists($this, 'getAdditionalInfo'))
					{
						foreach ($result as $row)
						{
							$row->getAdditionalInfo();
						}
					}
				}
			}
			
            return $result;
        }
        return [];
    }
	
	public function complexFind($paths = [], $params = [], $class = true, $dependencies = true)
	{
		$sql = '';
		$mainValues = (isset($params['values']) && is_array($params['values'])) ? implode(', ', $params['values']) : $params['values'];
		$mainValues = (!empty($mainValues)) ? $mainValues : '*';
		$bracketsCounter = 0;
		
		if (!empty($paths) && count($paths))
		{
			foreach ($paths as $pathName => $path)
			{
				$path = array_reverse($path);
				
				if (!empty($paths) && count($paths))
				{
					foreach ($path as $table => $values)
					{
						$valuesList = (!empty($mainValues)) ? $mainValues : array_pop($values);
						$whereValue = (is_array($values)) ? array_pop($values) : $values;
						unset($mainValues);
						
						$sql .= 'SELECT ' . $valuesList . ' FROM ' . $table . ' WHERE ';
						
						if ($bracketsCounter < count($path) - 1)
						{
							$sql .= $whereValue . ' IN (';
						}
						else 
						{
							$params['bind'] = reset($params['data'][$pathName]);
							$sql .= Helper::repeatString($whereValue . ' = ?', count($params['bind']), ' OR ');
						}
							
						$bracketsCounter++;
					}
				}
			}
			
			while ($bracketsCounter > 1)
			{
				
				$sql .= ')';
				$bracketsCounter--;
			}
			
			$sign = (preg_match('/^.* DESC$/i', $params['order'])) ? '<=' : '>=';
			$from = (isset($params['from'])) ? ' AND id ' . $sign . ' ' . $params['from'] : '';
			$order = (isset($params['order'])) ? ' ORDER BY ' . $params['order'] : '';
			$limit = (isset($params['limit'])) ? ' LIMIT ' . $params['limit'] : '';
			
			$sql .= $from . $order . $limit;
		}
		
		$class = ($class) ? get_class($this) : false;
		
		if (!$dependencies)
		{
			return $this->_db->query($sql, $params['bind'], $class)->results();
		}
		else
		{
			$results = $this->_db->query($sql, $params['bind'], $class)->results();
			ArrayHelper::callMethod($results, 'getAdditionalInfo');
			
			return $results;
		}
	}

    public function select($params = [])
    {
        return $this->_db->all($this->_table, $params);
    }

    public function lastSelectId()
    {
        return $this->_db->lastSelectId();
    }

    public function lastInsertId()
    {
        return $this->_db->lastInsertId();
    }

    public function count($params = [])
    {
        return $this->_db->selectCount($this->_table, $params);
    }

    public function insert($fields, $withDependencies = false)
    {
        if (empty($fields))
        {
            return false;
        }
		
        $result = $this->_db->insert($this->_table, $fields);
		
		if (!$withDependencies)
		{
			return $result;
		}
		
		if ($result)
		{
			$this->id = $this->lastInsertId();
			
			return $this->actOnDependencies('insert');
		}
    }

    public function insertBy($fields)
    {
        if (empty($fields))
        {
            return false;
        }

		$method = 'insert';
		
		foreach ($fields as $field => $value)
		{
			if (is_array($value))
			{
				$method = 'insertMultiple';
			}
		}
		
        return $this->_db->{$method}($this->_table, $fields);
    }

    public function update($id, $fields, $withDependencies = false, $action = 'update')
    {
        if (empty($fields) || $id == '')
        {
            return false;
        }
        
		$result = $this->_db->update($this->_table, $id, $fields);
		
		return (!empty($withDependencies)) ? $result && $this->actOnDependencies($action) : $result;
    }
	
	public function updateBy($fields, $params = [], $withDependencies = false, $action = 'update')
	{
		if (empty($fields))
        {
            return false;
        }
		
		$condition = $this->_prepareCondition($fields);
		$result = $this->_db->updateWhere($this->_table, $condition, $params);
		
		return (!empty($withDependencies)) ? $result && $this->actOnDependencies($action) : $result;
	}

    public function delete($id = '', $withDependencies = false)
    {
        if ($id == '' && $this->id == '')
        {
            return false;
        }

        $id = (!$this->id == '') ? $this->id : $id;
		
        if ($this->_softDelete)
        {
            return $this->update($id, ['deleted' => 1], $withDependencies, 'delete');
        }
		
        $result = $this->_db->delete($this->_table, $id);
		
		return (!empty($withDependencies)) ? $result && $this->actOnDependencies('delete') : $result;
    }
	
	public function deleteBy($fields)
	{
		if (empty($fields))
		{
			return false;
		}
		
		if ($this->_softDelete)
		{
			return $this->updateBy($fields, ['deleted' => 1]);
		}
		
		$condition = $this->_prepareCondition($fields);
		
        return $this->_db->deleteWhere($this->_table, $condition);
	}

	private function _prepareCondition($fields)
	{
		if (empty($fields) || !is_array($fields))
		{
			return false;
		}
		
		$condition = '';
		
		foreach ($fields as $column => $value)
		{
			if ($value == '' && $this->{$column} == '')
			{
				return false;
			}

			if (is_array($value))
			{
				$condition .= ' (';

				foreach ($value as $singleValue)
				{
					$condition .= $column . ' = ' . $singleValue . ' OR ';
				}

				$condition = preg_replace('/ OR $/', '', $condition);
				$condition .= ') ';
			}
			else
			{
				$condition .= $column . ' = ' . $value . ' AND ';
			}
		}
		
		return preg_replace('/ AND $/', '', $condition);
	}
	
    public function query($sql, $bind = [])
    {
        return $this->_db->query($sql, $bind);
    }

	public function popErrors()
    {
        $errors = $this->_errors;
        unset($this->_errors);

        return $errors;
    }
	
	public function compare($data)
	{
		$theSame = [];
		
		foreach ($data as $property => $value)
		{
			if (property_exists($this, $property) && $this->{$property} == $value)
			{
				$theSame[] = $property;
			}
		}
		
		return $theSame;
	}
	
	public function debugDumpParams()
	{
		$this->_db->debugDumpParams();
	}
}
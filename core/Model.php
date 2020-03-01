<?php


class Model
{
    protected $_db, $_table, $_softDelete = true, $lastSelectId, $modelMediator;

    public function __construct($table)
    {
        $this->_table = $table;
        $this->_db = Database::getInstance();
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
		if (!empty($data) && count($data))
		{
			foreach ($data as $key => $value)
			{
				if (property_exists($this, $key) && (empty($values) || in_array($key, $values)))
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
//			$class = get_class($this);
//
//			if ($result[0] instanceof $class && method_exists($this, 'getAdditionalInfo'))
//			{
//				foreach ($result as $row)
//				{
//					$row->getAdditionalInfo();
//				}
//			}
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

    public function insert($fields)
    {
        if (empty($fields))
        {
            return false;
        }
        return $this->_db->insert($this->_table, $fields);
    }

    public function update($id, $fields)
    {
        if (empty($fields) || $id == '')
        {
            return false;
        }
        return $this->_db->update($this->_table, $id, $fields);
    }

    public function delete($id = '')
    {
        if ($id == '' && $this->id == '')
        {
            return false;
        }

        $id = (!$this->id == '') ? $this->id : $id;

        if ($this->_softDelete)
        {
            return $this->update($id, ['deleted' => 1]);
        }
		
        return $this->_db->delete($this->_table, $id);
    }

    public function query($sql, $bind = [])
    {
        return $this->_db->query($sql, $bind);
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
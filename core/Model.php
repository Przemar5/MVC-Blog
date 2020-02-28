<?php


class Model
{
    protected $_db, $_table, $_softDelete = true, $lastSelectId;

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
        foreach ($data as $key => $value)
        {
            if (property_exists($this, $key) && (empty($values) || in_array($key, $values)))
            {
                $this->{$key} = $value;
            }
        }
		
		return $this;
    }

    public function find($params = [])
    {
        return (array) $this->_db->find($this->_table, $params, get_class($this));
    }

    public function findFirst($params = [])
    {
        return $this->_db->findFirst($this->_table, $params, get_class($this));
    }

    public function findById($id, $params = [])
    {
        $params['conditions'] = 'id = ? ';
        $params['bind'] = [$id];

        return $this->findFirst($params);
    }

    public function all($values = [], $class = false)
    {
        $class = ($class) ? get_class($this) : false;

        return $this->_db->all($this->_table, $values, $class);
    }

    public function last($amount = 1, $params = [])
    {
        if (is_numeric($amount))
        {
            $params['limit'] = $amount;
            $params['order'] = ' id DESC';

            $result = $this->find($params);
            $this->lastSelectId = $this->_db->lastSelectID();

            return $result;
        }
        return [];
    }

    public function lastFrom($amount = 1, $from = 0, $params = [])
    {
        if (is_numeric($amount))
        {
            $params['conditions'] = 'id <= ' . $from;
            $params['limit'] = $amount;
            $params['order'] = ' id DESC';

            $result = $this->find($params);
            $this->lastSelectId = $this->_db->lastSelectId();

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
}
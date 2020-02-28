<?php


class Database
{
    private static $_instance = null;
    private $_pdo, $_query, $_error = false, $_result, $_count = 0,
        $_lastSelectId = null, $_lastInsertId = null, $_lastTable = null, $_primaryKeyName = null;


    public function __construct()
    {
        try {
            $dsn = 'mysql:host=localhost;dbname=new_big_blog;';
            $this->_pdo = new PDO($dsn, 'root', '');
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function debugDumpParams()
    {
        return $this->_query->debugDumpParams();
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance))
        {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function insertMultiple($table, $params = [])
    {
        $fieldsString = '';
        $valuesString = '';

        // Determine array key name
        $valuesNum = 0;

        foreach ($params as $key => $value)
        {
            if (empty($value))
            {
                return;
            }

            $fieldsString .= "`$key`,";

            if (is_array($value) || is_object($value))
            {
                if (isset($array))
                {
                    return false;
                }
                $array = $key;
                $length = count($params[$key]);
            }

            $valuesNum++;
        }

        for ($i = 0; $i < $length; $i++)
        {
            $valuesString .= '(';

            for ($j = 0; $j < $valuesNum; $j++)
            {
                $valuesString .= '?, ';
            }

            $valuesString = rtrim($valuesString, ', ');
            $valuesString .= '), ';
        }

        $fieldsString = rtrim($fieldsString, ', ');
        $valuesString = rtrim($valuesString, ', ');

        $sql = 'INSERT INTO ' . $table . ' (' . $fieldsString . ') VALUES ' . $valuesString . ';';

        echo $sql;die;
        $c = 1;
        if ($this->_query = $this->_pdo->prepare($sql))
        {
            for ($i = 0; $i < $length; $i++)
            {
                foreach ($params as $key => $value)
                {
                    if ($key == $array)
                    {
                        $this->_query->bindValue($c, $value[$i]);
                    }
                    else
                    {
                        $this->_query->bindValue($c, $value);
                    }
                    $c++;
                }
            }

            if ($this->_query->execute())
            {
                return true;
            }
            else
            {
                $this->_error = true;
            }
        }

        return false;
    }

    public function query($sql, $params = [], $class = false)
    {
        $this->_error = false;

        if ($this->_query = $this->_pdo->prepare($sql))
        {
            $x = 1;

            if (count($params))
            {
                foreach ($params as $param)
                {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if ($this->_query->execute())
            {
                if ($class)
                {
                    $this->_result = $this->_query->fetchAll(PDO::FETCH_CLASS, $class);
                }
                else
                {
                    $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
                }

                $this->_count = $this->_query->rowCount();
                $this->_lastInsertId = $this->_pdo->lastInsertId();
            }
            else
            {
                $this->_error = true;
            }
        }

        return $this;
    }

    protected function _read($table, $params = [], $class = false)
    {
        $valueString = '*';
        $conditionString = '';
        $bind = [];
        $order = '';
        $limit = '';

        // Values
        if (isset($params['values']))
        {
            if (is_array($params['values']))
            {
                $valueString = implode(', ', $params['values']);
            }
            else if (is_string($params['values']))
            {
                $valueString = $params['values'];
            }
        }

        // Conditions
        if (isset($params['conditions']))
        {
            if (is_array($params['conditions']))
            {
                foreach ($params['conditions'] as $key => $value)
                {
                    if (gettype($value) === 'string')
                    {
                        $value = "'" . $value . "'";
                    }
                    $conditionString .= ' ' . $key . ' = ' . $value . ' AND';
                }
                $conditionString = trim($conditionString);
                $conditionString = rtrim($conditionString, ' AND');
            }
            else
            {
                $conditionString = $params['conditions'];
            }

            if ($conditionString != '')
            {
                $conditionString = ' WHERE ' . $conditionString;
            }
        }

        // Binding
        if (isset($params['bind']))
        {
            if (array_key_exists('bind', $params))
            {
                $bind = $params['bind'];
            }
        }

        // Order
        if (isset($params['order']))
        {
            if (array_key_exists('order', $params))
            {
                $order = ' ORDER BY ' . $params['order'];
            }
        }

        // Limit
        if (isset($params['limit']))
        {
            if (array_key_exists('limit', $params))
            {
                $limit = ' LIMIT ' . $params['limit'];
            }
        }

        $sql = 'SELECT ' . $valueString . ' FROM ' . $table . $conditionString . $order . $limit;

        if ($this->query($sql, $bind, $class))
        {
            $this->_lastTable = $table;

            if (!count($this->_result))
            {
                return false;
            }
            else
            {
                $lastId = $this->lastSelectID();

                if (!empty($lastId))
                {
                    $this->_lastSelectId = $lastId;
                }

                return true;
            }
        }
    }

    public function all($table, $params = [], $class = false)
    {
        return $this->find($table, $params, $class);
    }

    public function find($table, $params = [], $class = false)
    {
        if ($this->_read($table, $params, $class))
        {
            return $this->results();
        }
        return false;
    }

    public function findFirst($table, $params = [], $class = false)
    {
        if ($this->_read($table, $params, $class))
        {
            return $this->first();
        }
        return false;
    }

    public function selectCount($table, $params = [])
    {
        $params['values'] = ' COUNT(*) ';

        $this->findFirst($table, $params, false);

        if ($this->_read($table, $params, false))
        {
            return reset($this->results()[0]);
        }
        return false;
    }

    public function insert($table, $fields = [])
    {
        $fieldString = '';
        $valueString = '';
        $values = [];

        foreach ($fields as $field => $value)
        {
            $fieldString .= '`' . $field . '`,';
            $valueString .= '?,';
            $values[] = $value;
        }

        $fieldString = rtrim($fieldString, ',');
        $valueString = rtrim($valueString, ',');

        $sql = 'INSERT INTO ' . $table . ' (' . $fieldString . ') ' .
            'VALUES (' . $valueString . ')';

        if (!$this->query($sql, $values)->error())
        {
            return true;
        }
        return false;
    }

    public function update($table, $id, $fields = [])
    {
        $fieldString = '';
        $values = [];

        foreach ($fields as $field => $value)
        {
            $fieldString .= ' ' . $field . ' = ?,';
            $values[] = $value;
        }

        $fieldString = trim($fieldString);
        $fieldString = rtrim($fieldString, ',');
        $sql = 'UPDATE ' . $table . ' SET ' . $fieldString . ' WHERE id = ' . $id;

        if (!$this->query($sql, $values)->error())
        {
            return true;
        }
        return false;
    }

    public function delete($table, $id)
    {
        $sql = 'DELETE FROM ' . $table . ' WHERE id = ' . $id;

        if (!$this->query($sql)->error())
        {
            return true;
        }
        return false;
    }

    public function results()
    {
        return $this->_result;
    }

    public function first()
    {
        return (!empty($this->_result)) ? $this->_result[0] : [];
    }

    public function last()
    {
        return (!empty($this->_result)) ? $this->_result[$this->_count - 1] : [];
    }

    public function count()
    {
        return $this->_count;
    }

    public function lastInsertId()
    {
        return $this->_lastInsertId;
    }

    public function lastSelectID()
    {
        if (!$this->_lastSelectId)
        {
            $this->_lastSelectId = $this->last()->{$this->getPrimaryKeyName()};
        }

        return $this->_lastSelectId;
    }

    public function get_columns($table)
    {
        return $this->query('SHOW COLUMNS FROM ' . $table)->results();
    }

    public function error()
    {
        return $this->_error;
    }

    public function getPrimaryKeyName()
    {
        if (!empty($this->_lastTable))
        {
            return $this->_pdo->query("SHOW KEYS FROM $this->_lastTable WHERE Key_name = 'PRIMARY'")
                ->fetch(PDO::FETCH_OBJ)->Column_name;
        }
    }
}
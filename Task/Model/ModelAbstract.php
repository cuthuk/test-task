<?php


namespace Task\Model;

use Task\Db\MySql as Db;

abstract class ModelAbstract
{
    /**
     * TODO отрефакторить, вынести сборки where, set, и прочее
     * TODO добавление лимит и оффсет
     */
    protected $_query;
    protected $_limit;
    protected $_offset;
    protected $_table;

    /**
     * ModelAbstract constructor.
     */
    public function __construct()
    {
        $this->_table = $this->getEntity()->getTable();
    }

    abstract protected function getEntity($data = null);

    public function executeQuery()
    {
        $db = Db::getInstance();

        $result = $db->query($this->_query);
        return $result;
    }

    /**
     * @param $id
     * @return Entity
     */
    public function find($id)
    {

        $this->_query = "SELECT * FROM {$this->_table} where id = {$id}";
        $this->_limit = 1;
        $data = $this->executeQuery();
        $itemValues = $data->fetch(\PDO::FETCH_ASSOC);
        return $this->getEntity($itemValues);
    }

    /**
     * @param $where
     * @return Entity
     */
    public function findBy($where)
    {
        $whereString = '';
        foreach ($where as $condition => $value) {
            $value = $this->valueWrapper($value);
            $whereString .= " {$condition} {$value}";
        }
        $this->_query = "SELECT * FROM {$this->_table} where $whereString";
        $data = $this->executeQuery();
        $itemValues = $data->fetch(\PDO::FETCH_ASSOC);
        return $this->getEntity($itemValues);
    }

    public function create($data)
    {
        $columns = implode(',', array_keys($data));
        foreach ($data as &$value) {
            $value = $this->valueWrapper($value);
        }
        $values = implode(',', $data);
        $this->_query = "INSERT INTO {$this->_table}({$columns}) VALUES ({$values})";
        $result = $this->executeQuery();
        return ($result) ? $this->getEntity($data) : false;
    }

    public function update($data, $where = array())
    {

        if ($where === null) {
            if (isset($data['id'])) {
                $where['id'] = $data['id'];
                unset($data['id']);
            } else {
                return false;
            }
        } else {

        }
        $set = '';
        foreach ($data as $column => $value) {
            $value = $this->valueWrapper($value);
            $set .= "{$column} = {$value}";
        }
        $whereString = '';
        foreach ($where as $condition => $value) {
            $value = $this->valueWrapper($value);
            $whereString .= " {$condition} {$value}";
        }
        $this->_query = "UPDATE {$this->_table} SET {$set} WHERE {$whereString}";
        $data = $this->executeQuery();
        return ($data) ? true : false;
    }

    public function valueWrapper($value)
    {
        $db = Db::getInstance();
        return $db->getConnection()->quote($value);
    }
}
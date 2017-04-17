<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 12/04/17
 * Time: 18:00
 */

namespace Task\Model;

use Task\Db\MySql as Db;
use Task\Model\ModelAbstract as Model;
use Task\Entity\Comment as Entity;

class NestedAbstract extends Model
{
    protected function getEntity($data = null)
    {
        return new Entity($data);
    }

    public function insertChild($data, $parentId)
    {
        $insStr = $this->getInsertString(array_keys($data));
        $columnsStr = implode(',', array_keys($data));

        $db = Db::getInstance();
        $parentId = (int)$parentId;
        $stmt = $this->getStmt("
          SELECT @treeRight := rgt, @level := level from {$this->_table} WHERE id={$parentId};
          SELECT id FROM {$this->_table} WHERE rgt >= @treeRight FOR UPDATE;
        UPDATE {$this->_table} SET rgt=rgt+2 WHERE rgt >= @treeRight;
        UPDATE {$this->_table} SET lft=lft+2 WHERE lft > @treeRight; 
          INSERT INTO {$this->_table}(lft,rgt,level,{$columnsStr}) VALUES(@treeRight,@treeRight+1,@level+1, {$insStr});
          "
        );
        $db->getConnection()->beginTransaction();

        $this->bindParams($stmt, $data);
        $bool = $stmt->execute($data);
        $stmt->closeCursor();
        $insertId = $db->getConnection()->lastInsertId();
        if ($db->getConnection()->commit()) {

            return $this->find($insertId);
        } else {
            $db->getConnection()->rollBack();
            return null;
        }
    }

    public function delete($Id)
    {
        $db = Db::getInstance();
        $Id = (int)$Id;
        $stmt = $this->getStmt("
          SELECT @treeRight := rgt, @treeLeft := lft, @width := rgt - lft + 1 level from {$this->_table} WHERE id={$Id};
          SELECT id FROM {$this->_table} WHERE rgt >= @treeRight FOR UPDATE;
          DELETE FROM {$this->_table} WHERE lft BETWEEN @treeLeft AND @treeRight;
          UPDATE {$this->_table} SET rgt=rgt-@width WHERE rgt > @treeRight;
          UPDATE {$this->_table} SET lft=lft-@width WHERE lft > @treeRight;   
          "
        );
        $db->getConnection()->beginTransaction();
        $stmt->execute();
        $stmt->closeCursor();
        if ($db->getConnection()->commit()) {
            return true;
        } else {
            $db->getConnection()->rollBack();
            return false;
        }
    }

    public function update($data, $where = array())
    {
        $setStr = $this->getUpdateString($data);
        $whereStr = $this->getWhereString(array_keys($where));
        $db = Db::getInstance();
        $statement = "
            UPDATE {$this->_table} {$setStr} {$whereStr}
            ";
        $stmt = $this->getStmt($statement);
        $result = $stmt->execute(array_values($where));
        $stmt->closeCursor();
        return $result;
    }
    
    protected function getStmt($statement) {
        $db = Db::getInstance();
        return $db->getConnection()->prepare($statement);
    }

    // TODO перенести в modelAbstract и отрефакторить работу с ним
    protected function getUpdateString($dataKeys) {
        return count($dataKeys) ? " SET ".implode(',', array_map(function ($c, $v){
            return "{$c} = ".$this->valueWrapper($v);
        }, array_keys($dataKeys), $dataKeys)) : '';
    }

    protected function getInsertString($dataKeys) {
        return implode(',', array_map(function ($c){
            return Db::PLACEHOLDER_PREFIX."{$c}";
        }, $dataKeys));
    }

    protected function getWhereString($whereKeys) {
        return count($whereKeys) ? " WHERE ".implode(' ', $whereKeys) : '';
    }

    protected function bindParams( \PDOStatement &$stmt, &$params) {
        foreach ($params as $column => $value) {
            if (is_int($value)) {
                $stmt->bindValue(Db::PLACEHOLDER_PREFIX . $column, \PDO::PARAM_INT);
            } else {
                $stmt->bindValue(Db::PLACEHOLDER_PREFIX . $column, $value);
            }
        }
    }

    public function findAllBy($where) {
        $whereStr = $this->getWhereString(array_keys($where));
        $statement = "SELECT c.id, c.lft, c.rgt, c.level,c.author_id,c.comment, c.created, c.updated
          FROM {$this->_table} as c, {$this->_table} as pc {$whereStr} ORDER BY c.lft";
        $stmt = $this->getStmt($statement);
        $result = array();
        $query = $stmt->queryString;
        $where = array_filter(array_values($where), function($v){return !is_null($v);});
        if ($stmt->execute($where)) {
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $result[$row['id']] = $this->getEntity($row);
            }
        }
        return $result;
    }
}
<?php
namespace Task\Model;


use Task\Entity\Comment as Entity;
use Task\Db\MySql as MySql;
use Task\Model\NestedAbstract as NestedModel;

class CommentModel extends NestedModel
{
    // todo отрефакторить, сделав все через сущности
    protected function getEntity($data = null)
    {
        return new Entity($data);
    }

    public function getRoot()
    {
        return $this->getChildTree(0, true);
    }

    public function getChildTree($id = 0, $isRoot = false)
    {
        if ($isRoot) {
            return $this->findAllBy([
                'c.level = ?' => 0
            ]);
        } else {
            $children = $this->findAllBy([
                'pc.id = ? ' => $id,
                ' AND c.lft BETWEEN pc.lft AND pc.rgt ' => null,
            ]);
            $parent = array_shift($children);
            $this->buildTree($children, $parent);
            return $children;
        }
    }

    public function buildTree(&$children, &$parent) {
        foreach($children as &$child) {
            $childLevel = $child->getLevel();
            $val = $child->getLevel() - 1 == $parent->getLevel();
            if ($child->getLevel() - 1 == $parent->getLevel()) {
                $parent->children[] = $child;
            }
            if (($child->getRgt() - $child->getLft()) > 1) {
                $child->children = $this->buildTree($children, $child);
            }
        }
    }
}
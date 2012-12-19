<?php
/**
 * Author: andybaird
 * Date: 12/18/12
 */

namespace Schematik\Db;

use Schematik\Column;

class Table implements \ArrayAccess {
    protected $columns;
    protected $name;

    public function __construct($name)
    {
        $this->setName($name);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addColumn(Column $column) {
        $this->columns[] = $column;
    }

    public function getColumns() {
        return $this->columns;
    }

    public function findColumn($columnName) {
        foreach ($this->columns as $column) {
            if ($column->getName() == $columnName) {
                return $column;
            }
        }
    }

    public function offsetSet($offset, $value) {

        if (is_null($offset)) {
            $this->columns[] = $value;
        } else {
            if (!$value instanceof Column) {
                throw new Exception('Inserted column must be Column type');
            }
            $this->columns[$offset] = $value;
        }
    }
    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}